<?php
namespace App\Tests;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Uid64\Uid64;
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
    private static $client;

    public function setUp()
    {
        self::$client = self::createClient();
        self::createSchema();
    }

    /**
     * @return mixed
     */
    public function testJoin()
    {
        self::$client->request('POST', '/match');
        $this->assertEquals(201, self::$client->getResponse()->getStatusCode());
        $id = json_decode(self::$client->getResponse()->getContent(), true)['id'];
        return $id;
    }

    public function testDisplay()
    {
        $match = new Match(new MatchId());
        self::$kernel->getContainer()->get('app.repository.match')->persist($match);
        self::$client->request('GET', '/match/' . Uid64::toText($match->id()));
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
    }
}
