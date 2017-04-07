<?php
namespace Tests\AppBundle\Services;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CreateTicketTest extends WebTestCase
{
    public function testcreateTicket()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service = $container->get('app.createTicket');

        $client = new Client();
        $client->setEmail("info@trukotop.com");
        $client->setDate(new \DateTime());
        $client->setNbrTicket(4);
        $client->setDateReservation(new \DateTime('now'));;
        $client->setTypeTicket("Demi-journée");

        $service->createTicket($client);
        $result = count($client->getTickets());

        $this->assertEquals(4, $result);

    }
}