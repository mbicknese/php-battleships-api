<?php
namespace App\Tests\Controller;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Security\MatchPlayer;
use App\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShotControllerTest
 *
 * @package App\Tests\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShotControllerTest extends BaseTestCase
{
    private static $shotController;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::createSchema();
        self::$shotController = self::$kernel->getContainer()->get('app.controller.shot');
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

    public function testFire()
    {
        $controller = self::$shotController;
        $match = new Match(new MatchId());
        $match->progressToPhase(Match::PHASE_PLAYING);
        $matchPlayer = new MatchPlayer($match);
        $matchPlayer->setSequence(1);

        $response = $controller->fire($matchPlayer, new Request([], ['x' => 1, 'y' => 1]));
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testFireNotPlaying()
    {
        $controller = self::$shotController;
        $match = new Match(new MatchId());
        $matchPlayer = new MatchPlayer($match);
        $matchPlayer->setSequence(1);
        $response = $controller->fire($matchPlayer, new Request());

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testFireInvalidCoordinates()
    {
        $controller = self::$shotController;
        $match = new Match(new MatchId());
        $match->progressToPhase(Match::PHASE_PLAYING);
        $matchPlayer = new MatchPlayer($match);
        $matchPlayer->setSequence(1);

        $response = $controller->fire($matchPlayer, new Request());
        $this->assertEquals(400, $response->getStatusCode());

        $response = $controller->fire($matchPlayer, new Request([], ['x' => -1, 'y' => 1]));
        $this->assertEquals(400, $response->getStatusCode());
    }
}
