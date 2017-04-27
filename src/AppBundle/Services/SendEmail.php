<?php

/*
 * Function qui créer les tickets dans l'objet client
 * Argument : objet client
 *
 *
 */

namespace AppBundle\Services;

use AppBundle\Entity\Client;
use Symfony\Component\Templating\EngineInterface;

class SendEmail
{

    protected $templating;
    protected $mailer;

    public function __construct(EngineInterface $templating, \Swift_Mailer $mailer)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
    }


    /**
     * Function pour envoyer la commande en Email
     *
     * @param Client $client
     *
     */
    public function sendEmail(Client $client)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Réservation de tickets pour le musée du Louvre')
            ->setFrom(array('info@trukotop.com' => 'Le Louvre'))
            ->setTo($client->getEmail())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody(
                $this->templating->render('Emails/modelEmail.html.twig', array('client' => $client)),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}
