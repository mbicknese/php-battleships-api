<?php

namespace App\Tests\Controller;

use App\Controller\ItemController;
use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Tests\BaseTestCase;
use Symfony\Component\DomCrawler\Crawler;

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

    public function testItemListIsRendered()
    {
        self::bootKernel();
        $twig = self::$kernel->getContainer()->get('twig');
        $repository = new class extends ItemRepository {
            public function __construct(){}
            public function findAll()
            {
                return [
                    Item::fromName('Item 1'),
                    Item::fromName('Item 2'),
                    Item::fromName('Item 3'),
                    Item::fromName('Item 4'),
                ];
            }
        };
        $controller = new ItemController($twig, $repository);
        $response = $controller->index();
        $crawler = new Crawler($response->getContent());
        $nodeList = $crawler->filter('ul>li');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(4, $nodeList->count());
        $this->assertEquals('Item 1', $nodeList->first()->text());
        $this->assertEquals('Item 4', $nodeList->last()->text());
    }
}
