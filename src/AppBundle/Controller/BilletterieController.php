<?php

namespace AppBundle\Controller;

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
            $this->container->get('app.gestionClient')->setSessionClient($client);
            $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('msgFlash.infoBaseSucces'));
            return $this->redirectToRoute('fill_ticket', array('id' => $client->getId()));
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
            return $this->redirectToRoute('recap_command', array('id' => $client->getId()));
        }
        return $this->render('Billetterie/fillTicket.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/recapcommand", name="recap_command")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function recapCommand()
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
     * @Route("/checkout", name="order_checkout", methods="POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkoutAction(Request $request)
    {
        $client = $this->container->get('app.gestionClient')->getSessionClient();
        if ($client === null) { return $this->redirectToRoute('homepage'); }

        Stripe::setApiKey($this->getParameter('stripe_api_key'));
        // Get the credit card details submitted by the form
        // $token = $_POST['stripeToken'];
        $token = $_REQUEST['stripeToken'];

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
            $this->container->get('app.gestionClient')->setSessionClient($client);
            return $this->redirectToRoute('final_command', array('id' => $client->getId()));
        } catch(Card $e) {
            $this->addFlash("error",$this->get('translator')->trans('msgFlash.checkOutError'));
            return $this->redirectToRoute('recap_command', array('id' => $client->getId()));
            // The card has been declined
        }
    }

    /**
     * @Route("/finalcommand", name="final_command")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function finalCommand()
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
