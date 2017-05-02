<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Client;
use AppBundle\Form\Type\ClientInfoBaseType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\TicketArrayFormType;

class BilletterieController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     * * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->render('Billetterie/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/infobase", name="info_base")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function infoBaseAction(Request $request)
    {
        $client = new Client();
        $listDateDisabled = $this->container->get('app.generateListDateDisabled')->generateListDateDisabled();
        $form = $this->get('form.factory')->create(ClientInfoBaseType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('app.gestionClient')->setSessionClient($client);
            $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('msgFlash.infoBaseSucces'));
            return $this->redirectToRoute('fill_ticket');
        }
        return $this->render('Billetterie/infoBase.html.twig', array(
            'listDateDisabled' => $listDateDisabled,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/fillticket", name="fill_ticket")
     * @Method({"GET", "POST"})
     */
    public function fillTicketAction(Request $request)
    {
        $client = $this->container->get('app.gestionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }
        $this->container->get('app.createTicket')->createTicket($client);
        $form = $this->get('form.factory')->create(TicketArrayFormType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->container->get('app.gestionClient')->setSessionClient($client);
            return $this->redirectToRoute('recap_command');
        }
        return $this->render('Billetterie/fillTicket.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/recapcommand", name="recap_command")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Method({"GET"})
     */
    public function recapCommandAction()
    {
        $client = $this->container->get('app.gestionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }
        $this->container->get('app.generatePrices')->generatePrices($client);
        $this->container->get('app.gestionClient')->setSessionClient($client);
        return $this->render('Billetterie/recapCommand.html.twig', array(
            'client' => $client,
        ));
    }

    /**
     * @Route("/checkout", name="order_checkout")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Method({"POST"})
     */
    public function checkoutAction(Request $request)
    {
        $client = $this->container->get('app.gestionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }

        Stripe::setApiKey($this->getParameter('stripe_api_key'));
        // Get the credit card details submitted by the form
        $token = $request->request->get('stripeToken');


        // Create a charge: this will charge the user's card
        try {
            Charge::create(array(
                "amount" => $client->getPrixTotal()*100, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Musée du Louvre"
            ));
            $this->addFlash("success",$this->get('translator')->trans('msgFlash.checkOutSucces'));
            $client->setToken($token);
            $this->container->get('app.gestionClient')->setSessionClient($client);
            return $this->redirectToRoute('final_command', array('id' => $client->getId()));
        } catch(Card $e) {
            $this->addFlash("error",$this->get('translator')->trans('msgFlash.checkOutError'));
            return $this->redirectToRoute('recap_command');
            // The card has been declined
        }
    }

    /**
     * @Route("/finalcommand", name="final_command")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Method({"GET"})
     */
    public function finalCommandAction()
    {
        $client = $this->container->get('app.gestionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }
        $this->container->get('app.gestionClient')->saveClient($client);
        if ($client->getToken() || $client->getPrixTotal() == 0) {
            // Envoie Email
            $this->container->get('app.sendEmail')->sendEmail($client);
        }
        return $this->render('Billetterie/finalCommand.html.twig');
    }
}
