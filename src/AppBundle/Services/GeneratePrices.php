<?php

/*
 * Function qui  generer les prix de chaque ticket d'un client
 * Argument : objet Client
 *
 *
 */

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Client;

class GeneratePrices
{

    private $em;

    /**
     * ToolsBox constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, $getAge)
    {
        $this->em = $em;
        $this->getAge = $getAge;
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
                    $ticket->setPrix($priceRange[$this->getAge->getAge($ticket->getBirthday())]);
                } else {
                    $ticket->setPrix($tarifReduit);
                }
            } elseif ($client->getTypeTicket() == 'Demi-journée') {
                if ($ticket->getTarifReduit() == 0) {
                    $ticket->setPrix($priceRange[$this->getAge->getAge($ticket->getBirthday())] / 2);
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
}