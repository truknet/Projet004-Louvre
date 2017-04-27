<?php
namespace AppBundle\Services;

use AppBundle\Entity\Client;

class GeneratePrices
{

    /**
     * ToolsBox constructor.
     */
    public function __construct($getAge)
    {
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

        $coef = ($client->getTypeTicket() == Client::TYPE_DAY) ? 1 : 0.5;

        foreach ($client->getTickets() as $ticket) {
            if ($ticket->getTarifReduit() === false) {
                $ticket->setPrix($priceRange[$this->getAge->getAge($ticket->getBirthday())] * $coef);
            } else {
                if (($priceRange[$this->getAge->getAge($ticket->getBirthday())] * $coef) > ($tarifReduit * $coef)) {
                    $ticket->setPrix($tarifReduit * $coef);
                } else {
                    $ticket->setPrix($priceRange[$this->getAge->getAge($ticket->getBirthday())] * $coef);
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
