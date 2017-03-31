<?php


namespace Tests\AppBundle\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetAgeTest extends WebTestCase
{

    public function testGetAge()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        $service = $container->get('app.getAge');
        $result = $service->getAge(new \DateTime("2000/03/30"));
        $this->assertEquals(17, $result);
    }
}