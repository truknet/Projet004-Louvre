<?php

/*
 * Function qui gere les sessions, l'enregistrement et la récupération de l'objet client
 * Argument : objet client
 */

namespace AppBundle\Services;

use AppBundle\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GestionClient
{
    private $em;
    private $session;

    /**
     * GestionClient constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @param Client $client
     */
    public function setSessionClient(Client $client)
    {
        $this->session->set('tmpClient', $client);
    }

    /**
     * @return mixed|null
     */
    public function getSessionClient()
    {
        if ($this->session->get('tmpClient')) {
            $client = $this->session->get('tmpClient');
        } else {
            return null;
        }
        return $client;
    }

    /**
     * @param Client $client
     */
    public function saveClient(Client $client)
    {
        $this->em->persist($client);
        $this->em->flush();
        $this->session->remove('tmpClient');
    }
}