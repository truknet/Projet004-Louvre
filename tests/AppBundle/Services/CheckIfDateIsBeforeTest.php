<?php


namespace Tests\AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckIfDateIsBeforeTest extends WebTestCase
{


    public function testCheckIfDateIsBefore()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service = $container->get('app.checkIfDateIsBefore');

        $result = $service->checkIfDateIsBefore(new \DateTime("2000/03/30"));
        $this->assertEquals(false, $result);

        $result = $service->checkIfDateIsBefore(new \DateTime("2030/03/30"));
        $this->assertEquals(true, $result);

    }
}