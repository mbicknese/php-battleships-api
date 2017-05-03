<?php
namespace App\Tests\Controller;

use App\Controller\MatchController;
use App\Tests\BaseTestCase;
use Firebase\JWT\JWT;

/**
 * Class MatchControllerTest
 *
 * @package App\Tests\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchControllerTest extends BaseTestCase
{
    /**
     * @var MatchController
     */
    protected static $matchController;

    public static function setUpBeforeClass()
    {
        self::createClient();
        self::createSchema();
        self::$matchController = self::$kernel->getContainer()->get('app.controller.match');
    }
    public function tearDown()
    {
        // Prevent default tearDown
    }
    public static function tearDownAfterClass()
    {
        self::ensureKernelShutdown();
        parent::tearDownAfterClass();
    }

    public function testJoinMatch()
    {
        $response = self::$matchController->joinMatch();
        $content = json_decode($response->getContent(), true);
        $jwtContent = JWT::decode(
            $response->headers->get('Authorization'),
            self::$kernel->getContainer()->getParameter('env(APP_SECRET)'),
            ['HS256']
        );

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('location', $response->headers->all());
        $this->assertEquals(1, $content['player']);
        $this->assertEquals($content['player'], $jwtContent->player);
        $this->assertInternalType('string', $jwtContent->id);

        $response = self::$matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(2, $content['player']);

        $response = self::$matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(1, $content['player']);
    }

    public function testDisplayMatch()
    {
        $joinMatchResponse = self::$matchController->joinMatch();
        $joinMatchContent = json_decode($joinMatchResponse->getContent(), true);
        $displayMatchResponse = self::$matchController->displayMatch($joinMatchContent['id']);
        $displayMatchContent = json_decode($displayMatchResponse->getContent(), true);

        $this->assertInternalType('int', $displayMatchContent['phase']);
        $this->assertInternalType('int', $displayMatchContent['current_player']);
        $this->assertInternalType('array', $displayMatchContent['ships']);
        $this->assertCount(2, $displayMatchContent['grid']);
        $this->assertInternalType('array', $displayMatchContent['shots']);
    }
}
