<?php

namespace Tests\AppBundle\Services;


use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ControleDateTest extends WebTestCase
{

    /**
     *
     */
    public function testControleDate()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service = $container->get('app.controleDate');

        $client = new Client();
        $client->setDateReservation(new \DateTime("01-04-2017"));
        $client->setTypeTicket('journée');
        $client->setEmail('info@truknet.com');

        $result = $service->controleDate($client);
        $this->assertEquals(true, $result);
    }
}