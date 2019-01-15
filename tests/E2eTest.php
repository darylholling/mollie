<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class E2eTest extends PantherTestCase
{
    public function testMyApp()
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');


//        $this->assertGreaterThan(
//            0,
//            $crawler->filter('html:contains("boeken")')->count()
//        );

        $link = $crawler->selectLink('Boeken')->link();
        $client->click($link);

//        $client->takeScreenshot('screen.png'); // Yeah, screenshot!

//        $client->waitFor('.cartadd');
        $client->wait(3);

//        echo $crawler->filter('.cartadd')->text();
        $client->takeScreenshot('screen2.png'); // Yeah, screenshot!
    }
}