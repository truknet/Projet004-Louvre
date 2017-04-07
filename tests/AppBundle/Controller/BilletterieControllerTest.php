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
        $this->assertContains('title.body.titleh1', $crawler->filter('h1')->text());
        $link = $crawler
            ->filter('a:contains("button.booking")')
            ->eq(0)
            ->link()
        ;
        $client->click($link);
    }

    public function testInfoBaseAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/infobase');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h3:contains("title.page.etape1")')->count());

        $form = $crawler->selectButton('button.validate')->form();
        $form['appbundle_client[email][first]'] = 'info@truknet.com';
        $form['appbundle_client[email][second]'] = 'info@truknet.com';
        $form['appbundle_client[dateReservation]'] = (new \DateTime())->format('Y-m-d');
        $form['appbundle_client[nbrTicket]'] = 1;
        $form['appbundle_client[typeTicket]'] = 'Journée';
        $crawler = $client->submit($form);
    }

    /**
     *
     */
    public function testFillTicketAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/fillticket');

        $clientX = new Client();
        $clientX->setDate(new \DateTime());
        $clientX->setEmail('info@truknet.com');
        $clientX->setDateReservation(new \DateTime());
        $clientX->setNbrTicket(1);
        $clientX->setTypeTicket('Journée');


        $form = $crawler->filter('input[type=submit]')->form();

        $form['ticket_array_form[tickets][0][name]'] = 'Justine';
        $form['ticket_array_form[tickets][0][firstname]'] = 'Roche';
        $form['ticket_array_form[tickets][0][country]'] = 'FR';
        $form['ticket_array_form[tickets][0][birthday][day]'] = 11;
        $form['ticket_array_form[tickets][0][birthday][month]'] = 5;
        $form['ticket_array_form[tickets][0][birthday][year]'] = 2001;
        $form['ticket_array_form[tickets][0][tarifReduit]'] = false;
        $crawler = $client->submit($form);

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
