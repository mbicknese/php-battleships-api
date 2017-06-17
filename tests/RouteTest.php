<?php
namespace App\Tests;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Uid64\Uid64;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

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
        $this->requestAuthenticated('POST', '/ship', [
            'start_x' => 1,
            'start_y' => 1,
            'end_x'   => 5,
            'end_y'   => 1,
        ]);
        $this->assertEquals(200, self::$client->getResponse()->getStatusCode());
    }

    public function testFireShot()
    {
        $match = new Match(new MatchId());
        $match->progressToPhase(Match::PHASE_PLAYING);
        $this->requestAuthenticated('POST', '/shot', [
            'x' => 1,
            'y' => 1,
        ], [], [], $match);
        $response = self::$client->getResponse();

        $this->assertEquals(201, $response->getStatusCode(), $response->getContent());
    }

    /**
     * Makes a HTTP request which is pre authenticated
     *
     * @param string $method
     * @param string $uri
     * @param array $parameters (optional)
     * @param array $files (optional)
     * @param array $server (optional)
     * @param Match|null $match
     * @return Crawler
     */
    protected function requestAuthenticated(
        string $method,
        string $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        Match $match = null
    ): Crawler {
        if (!$match) {
            $match = new Match(new MatchId());
        }
        self::$kernel->getContainer()->get('app.repository.match')->persist($match);
        $validJWT = self::$kernel->getContainer()->get('app.jwt')->encode([
            'id'     => Uid64::toText($match->id()),
            'player' => 1
        ]);
        $authorizationHeader = 'bearer ' . $validJWT;
        return self::$client->request(
            $method,
            $uri,
            $parameters,
            $files,
            array_merge($server, ['HTTP_Authorization' => $authorizationHeader])
        );
    }
}
