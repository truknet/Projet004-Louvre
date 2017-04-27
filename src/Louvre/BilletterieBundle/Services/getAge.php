<?php

namespace Louvre\BilletterieBundle\ToolsBox;

use Doctrine\ORM\EntityManagerInterface;
use Louvre\BilletterieBundle\Entity\Client;
use Louvre\BilletterieBundle\Entity\Ticket;


class ToolsBox
{

    private $em;

    /**
     * ToolsBox constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Function pour créer les tickets dans l'objet client
     *
     * @param Client $client
     *
     */
    public function createTicket(Client $client)
    {
        $iTickets = $client->getNbrTicket()-count($client->getTickets());
        for ($i = 0; $i < $iTickets; $i++)
        {
            $tickets[$i] = new Ticket();
            $tickets[$i]->setClient($client);
            $client->getTickets()->add($tickets[$i]);
        }
    }


    /**
     * Function pour generer les prix de chaque ticket
     *
     * @param Client $client
     *
     */
    public function generatePrices(Client $client)
    {
        // Arguments du tableau (Age de debut / nbr d'année / Tarif)
        $tranche1 = array_fill(0, 4, 0); // prix de 0€ pour les enfants de 0 à 3 ans
        $tranche2 = array_fill(5, 8, 8); // prix de 8€ pour les enfants de 4 à 11 ans
        $tranche3 = array_fill(12, 48, 16); // prix de 16€ pour la tranche d'age de 12 à 59 ans
        $tranche4 = array_fill(60, 60, 12); // prix de 0€ pour les seniors à partir de 60 ans
        $priceRange = array_merge($tranche1, $tranche2, $tranche3, $tranche4);
        $tarifReduit = 10; // Prix pour les tarifs réduits

        foreach ($client->getTickets() as $ticket) {
            if ($client->getTypeTicket() == 'Journée') {
                if ($ticket->getTarifReduit() == 0) {
                    $ticket->setPrix($priceRange[$this->getAge($ticket->getBirthday())]);
                } else {
                    $ticket->setPrix($tarifReduit);
                }
            } elseif ($client->getTypeTicket() == 'Demi-journée') {
                if ($ticket->getTarifReduit() == 0) {
                    $ticket->setPrix($priceRange[$this->getAge($ticket->getBirthday())] / 2);
                } else {
                    $ticket->setPrix($tarifReduit / 2);
                }
            }
        }
        $client->setPrixTotal(0);
        $total = 0;
        foreach ($client->getTickets() as $ticket) {
            $total = $total + $ticket->getPrix();
        }
        $client->setPrixTotal($total);
    }

    /**
     * Function pour calculer l'age
     *
     * @param $date
     * @return mixed
     */
    public function getAge($date)
    {
        $dateInterval = $date->diff(new \DateTime());
        return $dateInterval->y;
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
                $this->renderView('Emails/modelEmail.html.twig', array('client' => $client)),
                'text/html'
            )

        ;
        $this->get('mailer')->send($message);
    }

}