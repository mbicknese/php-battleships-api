<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class HttpTest
 *
 * @package App\Tests
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class RouteTest extends BaseTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        self::createSchema();
    }

    public function testJoin()
    {
        $this->client->request('POST', '/match');
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }
}
