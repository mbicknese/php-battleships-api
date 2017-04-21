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

    public function testJoin()
    {
        self::$client->request('POST', '/match');
        $this->assertEquals(201, self::$client->getResponse()->getStatusCode());
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
        $validJWT = self::$kernel->getContainer()->get('app.jwt')->encode([
            'id'     => Uid64::toText($match->id()),
            'player' => 1
        ]);
        $authorizationHeader = 'bearer ' . $validJWT;
        self::$client->request('POST', '/ship', [
            'start_x' => 1,
            'start_y' => 1,
            'end_x'   => 5,
            'end_y'   => 1,
        ], [], ['HTTP_Authorization' => $authorizationHeader]);
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
    }
}
