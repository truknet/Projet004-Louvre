<?php


namespace Tests\AppBundle\Services;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CheckIf14HourTest extends WebTestCase
{
    public function testcheckIf14Hour()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service = $container->get('app.checkIf14Hour');

        $client = new Client();
        $client->setEmail("info@trukotop.com");
        $client->setDate(new \DateTime());
        $client->setNbrTicket(1);
        $client->setDateReservation(new \DateTime('now'));;

        $client->setTypeTicket("Demi-journée");
        $result = $service->checkIf14Hour($client);
        $this->assertEquals(true, $result);

        $client->setTypeTicket("Journée");
        $result = $service->checkIf14Hour($client);
        if (((new \DateTime('now'))->format('H')) > 14)
        {
            $this->assertEquals(false, $result);
        } else {
            $this->assertEquals(true, $result);
        }
    }
}