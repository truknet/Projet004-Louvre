<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class BilletterieControllerTest extends WebTestCase
{

    public function testIndexAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Découvrir le Louvre !', $crawler->filter('h1')->text());
        $link = $crawler
            ->filter('a:contains("Réservation")')
            ->eq(0)
            ->link()
        ;
        $crawler = $client->click($link);
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3:contains("Etape 1")')->count());
    }

    public function testInfoBaseAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/infobase');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3:contains("Etape 1")')->count());

        $form = $crawler->selectButton('Valider')->form();
        $form['appbundle_client[email][first]'] = 'info@truknet.com';
        $form['appbundle_client[email][second]'] = 'info@truknet.com';
        $form['appbundle_client[dateReservation]'] = '04 04 2018';
        $form['appbundle_client[nbrTicket]'] = 1;
        $form['appbundle_client[typeTicket]'] = 'Demi-journée';
        $crawler = $client->submit($form);

        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testFillTicketAction()
    {
        $client = new Client();
        $client->setDate(new \DateTime());
        $client->setEmail('info@truknet.com');
        $client->setDateReservation(new \DateTime());
        $client->setNbrTicket(1);
        $client->setTypeTicket('Demi-journée');

        $clientReq = static::createClient();
        $container = $clientReq->getContainer();
        $container->get('app.gestionClient')->setSessionClient($client);
        $crawler = $clientReq->request('GET', '/fr/fillticket');
        $this->assertTrue($clientReq->getResponse()->isSuccessful());
        $this->assertTrue(200 === $clientReq->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3:contains("Etape 2 - Remplir les Tickets")')->count());

        $form = $crawler->selectButton('Valider')->form();
        $form['ticket_array_form[tickets][0][name]'] = 'Justine';
        $form['ticket_array_form[tickets][0][firstname]'] = 'Roche';
        $form['ticket_array_form[tickets][0][country]'] = 'FR';
        $form['ticket_array_form[tickets][0][birthday][day]'] = 11;
        $form['ticket_array_form[tickets][0][birthday][month]'] = 5;
        $form['ticket_array_form[tickets][0][birthday][year]'] = 2001;
        $form['ticket_array_form[tickets][0][tarifReduit]'] = false;
        $crawler = $clientReq->submit($form);

        // $clientReq->followRedirect();
        $this->assertTrue($clientReq->getResponse()->isSuccessful());
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * @return array
     */
    public function urlProvider()
    {
        return array(
            array('/fr/'),
            array('/fr/infobase'),
        );
    }
}
