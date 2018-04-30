<?php

namespace Tests\Snowtricks\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLoginpage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testResetPassLink()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $link = $crawler->selectLink('Mot de passe oublié ?')->link();
        $crawler = $client->click($link);

        $info = $crawler->filter('h2')->text();

        $this->assertSame('Réinitialisez votre mot de passe', $info);
    }

    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/register');

        $form = $crawler->selectButton('S\'enregistrer')->form();
        $form['snowtricks_platformbundle_user[username]'] = 'Username';
        $form['snowtricks_platformbundle_user[email]'] = 'email@example.com';
        $form['snowtricks_platformbundle_user[plainPassword][first]'] = 'password';
        $form['snowtricks_platformbundle_user[plainPassword][second]'] = 'password';
        $crawler = $client->submit($form);

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }
}