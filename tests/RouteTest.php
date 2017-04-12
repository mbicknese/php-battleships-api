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
     * @return string JWT to authenticate further calls
     */
    public function testJoin()
    {
        self::$client->request('POST', '/match');
        $this->assertEquals(201, self::$client->getResponse()->getStatusCode());
        return self::$client->getResponse()->headers->get('Authorization');
    }

    public function testDisplay()
    {
        $match = new Match(new MatchId());
        self::$kernel->getContainer()->get('app.repository.match')->persist($match);
        self::$client->request('GET', '/match/' . Uid64::toText($match->id()));
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
    }

    public function testPlaceShip()
    {
        $match = new Match(new MatchId());
        self::$kernel->getContainer()->get('app.repository.match')->persist($match);
//        $validJWT =
    }
}
