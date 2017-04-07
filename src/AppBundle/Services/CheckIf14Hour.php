<?php

namespace AppBundle\Services;

use AppBundle\Entity\Client;

class CheckIf14Hour
{
    /**
     * Controle si la date de reservation est égale à la date du jour
     * Si oui -> Controle si l'heure est pas égale ou supérieur à 14 heure
     * Si oui -> Controle si le type de billet est pas égale à un ticket Journée
     * Si oui -> Retourne une erreur
     *
     * @param Client $client
     * @return bool
     */
    public function checkIf14Hour(Client $client)
    {
        $dateNow = new \DateTime('NOW');
        if (
            $dateNow->format('Y-m-d') == $client->getDateReservation()->format('Y-m-d')
            && intval($dateNow->format('H'), 10) >= 14
            && $client->getTypeTicket() == "Journée")
        {
            return false;
        }
        return true;
    }
}