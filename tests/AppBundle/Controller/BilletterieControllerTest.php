<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilletterieControllerTest extends WebTestCase
{

    /**
     *
     */
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Découvrir le Louvre !', $crawler->filter('h1')->text());
    }

    /**
     *
     */
    public function testSetEmail()
    {
        $client = new Client();
        $client->setEmail('info@truknet.com');
        $this->assertEquals('info@truknet.com', $client->getEmail());
    }

    /**
     *
     */
    public function testPageInfoBase()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/infobase');
        $this->assertEquals(1, $crawler->filter('h3:contains("Etape 1 - Demande d\'informations")')->count());
        $form = $crawler->selectButton('Valider')->form(array(
            'appbundle_client[email][first]'        => 'info@truknet.com',
            'appbundle_client[email][second]'       => 'info@truknet.com',
            'appbundle_client[dateReservation]'     => '01/04/2017',
            'appbundle_client[nbrTicket]'           => 1,
            'appbundle_client[typeTicket]'          => 'Journée',
        ));
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
            array('/'),
            array('/infobase'),
        );
    }


}
