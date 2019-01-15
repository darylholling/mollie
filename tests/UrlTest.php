<?php

namespace App\Tests;


use Symfony\Component\Panther\PantherTestCase;

class UrlTest extends PantherTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $crawler = $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return array(
            array('/'),
            array('/factuur'),
            array('/login'),
            array('/register'),
        );
    }


}