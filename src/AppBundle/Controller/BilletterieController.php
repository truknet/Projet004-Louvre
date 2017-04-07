<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation;
use AppBundle\Entity\Client;
use AppBundle\Form\ClientInfoBaseType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\TicketArrayFormType;
use Symfony\Component\Validator\Constraints as Assert;

class BilletterieController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('Billetterie/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/infobase", name="info_base")
     * @param Request $request
     */
    public function infoBaseAction(Request $request)
    {
        $client = new Client();
        $listDateDisabled = $this->container->get('app.generateListDateDisabled')->generateListDateDisabled();

        $form = $this->get('form.factory')->create(ClientInfoBaseType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('app.setSessionClient')->setSessionClient($client);
            $request->getSession()->getFlashBag()->add('info', $this->get('translator')->trans('msgFlash.infoBaseSucces'));
            return $this->redirectToRoute('fill_ticket', array('id' => $client->getId()));
        }
        return $this->render('Billetterie/infoBase.html.twig', array(
            'listDateDisabled' => $listDateDisabled,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/fillticket", name="fill_ticket")
     * @param Request $request
     * @return Response
     */
    public function fillTicketAction(Request $request)
    {
        $client = $this->container->get('app.getSessionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }
        $this->container->get('app.createTicket')->createTicket($client);
        $form = $this->get('form.factory')->create(TicketArrayFormType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('app.setSessionClient')->setSessionClient($client);
            return $this->redirectToRoute('recap_command', array('id' => $client->getId()));
        }
        return $this->render('Billetterie/fillTicket.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     * @param Request $request
     * @Route("/recapcommand", name="recap_command")
     */
    public function recapCommand(Request $request)
    {
        $client = $this->container->get('app.getSessionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }
        $this->container->get('app.generatePrices')->generatePrices($client);
        $this->container->get('app.setSessionClient')->setSessionClient($client);
        return $this->render('Billetterie/recapCommand.html.twig', array(
            'client' => $client,
        ));
    }

    /**
     * @Route(
     *     "/checkout",
     *     name="order_checkout",
     *     methods="POST"
     * )
     * @return Response
     * @param Request $request
     */
    public function checkoutAction(Request $request)
    {
        $client = $this->container->get('app.getSessionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }

        Stripe::setApiKey($this->getParameter('stripe_api_key'));
        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];
        // Create a charge: this will charge the user's card
        try {
            $charge = Charge::create(array(
                "amount" => $client->getPrixTotal()*100, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Musée du Louvre"
            ));
            $this->addFlash("success",$this->get('translator')->trans('msgFlash.checkOutSucces'));
            $client->setToken($token);
            $this->container->get('app.setSessionClient')->setSessionClient($client);
            return $this->redirectToRoute('final_command', array('id' => $client->getId()));
        } catch(Card $e) {
            $this->addFlash("error",$this->get('translator')->trans('msgFlash.checkOutError'));
            return $this->redirectToRoute('recap_command', array('id' => $client->getId()));
            // The card has been declined
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/finalcommand", name="final_command")
     */
    public function finalCommand(Request $request)
    {
        $client = $this->container->get('app.getSessionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }
        $this->container->get('app.saveClient')->saveClient($client);
        if ($client->getToken()) {
            // Envoie Email
            $this->container->get('app.sendEmail')->sendEmail($client);
        }
        return $this->render('Billetterie/finalCommand.html.twig');
    }
}
