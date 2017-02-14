<?php
namespace App\Tests\Controller;

use App\Tests\BaseTestCase;

class ItemControllerTest extends BaseTestCase
{
    public function testIndex()
    {
        $client = self::createClient();
        self::createSchema();
        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h1:contains("Hello World")')->count());
    }
}
