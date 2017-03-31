<?php

/*
 * Function qui créer les tickets dans l'objet client
 * Argument : objet client
 */

namespace AppBundle\Services;

use AppBundle\Entity\Client;
use AppBundle\Entity\Ticket;


class CreateTicket
{
    /**
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
}