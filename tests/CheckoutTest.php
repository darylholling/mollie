<?php
/**
 * Created by PhpStorm.
 * User: DarylHolling
 * Date: 15-1-2019
 * Time: 11:36
 */

namespace App\Tests;


use Symfony\Component\Panther\PantherTestCase;

class CheckoutTest extends PantherTestCase
{
    public function testMyApp()
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', '/');
//
////        link naar de boeken
        $link = $crawler->selectLink('Boeken')->link();
        $client->click($link);
        $client->takeScreenshot('screen.png'); // Yeah, screenshot!
        $client->wait(5);

//

        $this->assertEquals();

////        link naar de winkelwagen
        $ww = $crawler->selectLink('Winkelwagen')->link();
//        $button = $crawler
//            ->filter('a:contains("ADD TO CART")') // find all buttons with the text "Add"
//            ->eq(0) // select the first button in the list
//            ->link(); // and click it
        $client->click($ww);
        $client->takeScreenshot('screen2.png'); // Yeah, screenshot!
        $client->wait(5);

        $this->assertEquals();
        ////      winkelwagen
//        $link = $crawler->selectLink('Winkelwagen')->link();
//        $client->click($link);
//        $client->takeScreenshot('screen.png'); // Yeah, screenshot!
//        $client->wait(5);

////        checkout
//        $link = $crawler->selectLink('Checkout')->link();
//        $client->click($link);
//        $client->takeScreenshot('screen2.png'); // Yeah, screenshot!
//        $client->wait(5);

////        ing
//        $link = $crawler->selectLink('Ing')->link();
//        $client->click($link);
//        $client->takeScreenshot('screen3.png'); // Yeah, screenshot!
//        $client->wait(5);

////        betaald
//        $link = $crawler->selectLink('Betaald')->link();
//        $client->click($link);
//        $client->takeScreenshot('screen4.png'); // Yeah, screenshot!
//        $client->wait(5);

////        verder
//        $link = $crawler->selectLink('Ga verder')->link();
//        $client->click($link);
//        $client->takeScreenshot('screen5.png'); // Yeah, screenshot!
//        $client->wait(5);


    }
}