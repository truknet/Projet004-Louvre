<?php

/*
 * Function qui controle la validité de la date de réservation coté serveur
 *
 * Controle si la date de reservation n'est pas incluse dans la listDateDisabled
 * Controle si la date de reservation est = à la date du jour et qu'il n'est pas plus de 14h
 *
 */

namespace AppBundle\Services;


use AppBundle\Entity\Client;

class ControleDate
{

    /**
     * ToolsBox constructor.
     */
    public function __construct($generateListDateDisabled)
    {
        $this->generateListDateDisabled = $generateListDateDisabled;
    }


    /**
     * @param Client $client
     * @return bool
     */
    public function controleDate(Client $client)
    {

        $dateTmp = $client->getDateReservation()->format('d-m-Y');
        $listDateDisabled = $this->generateListDateDisabled->generateListDateDisabled();
        if (in_array($dateTmp, $listDateDisabled)) { return false; }

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