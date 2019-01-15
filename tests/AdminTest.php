<?php


namespace App\Tests;


use Symfony\Component\Panther\PantherTestCase;

class AdminTest extends PantherTestCase
{
    public function testMyApp()
    {

        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form();

        $form['_username'] = 'adminuser';
        $form['_password'] = 'admin';

        $client->submit($form);
    }

}