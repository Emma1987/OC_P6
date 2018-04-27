<?php

namespace Tests\Snowtricks\PlatformBundle\Controller;

use Snowtricks\PlatformBundle\Entity\TrickGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddTrick()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/trick/add');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testAllTricks()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/trick/all');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test404()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/azerty');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
