<?php


namespace Tests\AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckIfDateInListTest extends WebTestCase
{


    public function testCheckIfDateInList()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service = $container->get('app.checkIfDateInList');

        $result = $service->checkIfDateInList(new \DateTime("2018/05/01"));
        $this->assertEquals(false, $result);

        $result = $service->checkIfDateInList(new \DateTime("2030/03/30"));
        $this->assertEquals(true, $result);

    }
}