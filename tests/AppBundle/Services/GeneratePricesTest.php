<?php
namespace Tests\AppBundle\Services;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class GeneratePricesTest extends WebTestCase
{
    public function testGeneratePrices1()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service1 = $container->get('app.createTicket');
        $service2 = $container->get('app.generatePrices');

        $client = new Client();
        $client->setEmail("info@trukotop.com");
        $client->setDate(new \DateTime());
        $client->setNbrTicket(4);
        $client->setDateReservation(new \DateTime('now'));;
        $client->setTypeTicket("Demi-journée");

        $service1->createTicket($client);

        $client->getTickets()[0]->setName('Jean');
        $client->getTickets()[0]->setFirstname('Roche');
        $client->getTickets()[0]->setCountry('FR');
        $client->getTickets()[0]->setTarifReduit(true);
        $client->getTickets()[0]->setBirthday(new \DateTime('1975-05-02'));

        $client->getTickets()[1]->setName('Justine');
        $client->getTickets()[1]->setFirstname('Roche');
        $client->getTickets()[1]->setCountry('FR');
        $client->getTickets()[1]->setTarifReduit(false);
        $client->getTickets()[1]->setBirthday(new \DateTime('1982-07-07'));

        $client->getTickets()[2]->setName('Theo');
        $client->getTickets()[2]->setFirstname('Roche');
        $client->getTickets()[2]->setCountry('FR');
        $client->getTickets()[2]->setTarifReduit(false);
        $client->getTickets()[2]->setBirthday(new \DateTime('2000-01-12'));

        $client->getTickets()[3]->setName('Angele');
        $client->getTickets()[3]->setFirstname('Roche');
        $client->getTickets()[3]->setCountry('FR');
        $client->getTickets()[3]->setTarifReduit(false);
        $client->getTickets()[3]->setBirthday(new \DateTime('2006-05-15'));

        $service2->generatePrices($client);

        $result = 0;
        foreach ($client->getTickets() as $ticket)
        {
            $result = $result + $ticket->getPrix();
        }
        $this->assertEquals(25, $result);
        $this->assertEquals(25, $client->getPrixTotal());
    }


    public function testGeneratePrices2()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service1 = $container->get('app.createTicket');
        $service2 = $container->get('app.generatePrices');

        $client = new Client();
        $client->setEmail("info@trukotop.com");
        $client->setDate(new \DateTime());
        $client->setNbrTicket(6);
        $client->setDateReservation(new \DateTime('now'));;
        $client->setTypeTicket("Journée");

        $service1->createTicket($client);

        $client->getTickets()[0]->setName('Jean');
        $client->getTickets()[0]->setFirstname('Roche');
        $client->getTickets()[0]->setCountry('FR');
        $client->getTickets()[0]->setTarifReduit(true);
        $client->getTickets()[0]->setBirthday(new \DateTime('1975-05-02'));

        $client->getTickets()[1]->setName('Justine');
        $client->getTickets()[1]->setFirstname('Roche');
        $client->getTickets()[1]->setCountry('FR');
        $client->getTickets()[1]->setTarifReduit(false);
        $client->getTickets()[1]->setBirthday(new \DateTime('1982-07-07'));

        $client->getTickets()[2]->setName('Theo');
        $client->getTickets()[2]->setFirstname('Roche');
        $client->getTickets()[2]->setCountry('FR');
        $client->getTickets()[2]->setTarifReduit(false);
        $client->getTickets()[2]->setBirthday(new \DateTime('2000-01-12'));

        $client->getTickets()[3]->setName('Angele');
        $client->getTickets()[3]->setFirstname('Roche');
        $client->getTickets()[3]->setCountry('FR');
        $client->getTickets()[3]->setTarifReduit(false);
        $client->getTickets()[3]->setBirthday(new \DateTime('2006-05-15'));

        $client->getTickets()[4]->setName('Eva');
        $client->getTickets()[4]->setFirstname('Roche');
        $client->getTickets()[4]->setCountry('FR');
        $client->getTickets()[4]->setTarifReduit(true);
        $client->getTickets()[4]->setBirthday(new \DateTime('2015-06-10'));

        $client->getTickets()[5]->setName('Norbert');
        $client->getTickets()[5]->setFirstname('Roche');
        $client->getTickets()[5]->setCountry('FR');
        $client->getTickets()[5]->setTarifReduit(true);
        $client->getTickets()[5]->setBirthday(new \DateTime('1948-03-16'));


        $service2->generatePrices($client);

        $result = 0;
        foreach ($client->getTickets() as $ticket)
        {
            $result = $result + $ticket->getPrix();
        }
        $this->assertEquals(60, $result);
        $this->assertEquals(60, $client->getPrixTotal());
    }
}