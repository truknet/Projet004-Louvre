<?php

/*
 * Function qui créer les tickets dans l'objet client
 * Argument : objet client
 */

namespace AppBundle\Services;

use AppBundle\Entity\Client;

class CreateUniqId
{
    /**
     *
     * @param Client $client
     *
     */
    public function createUniqId(Client $client)
    {
        $client->setUniquid(uniqid());
    }
}