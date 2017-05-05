<?php
namespace App\Tests\Controller;

use App\Controller\ShotController;
use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Security\MatchPlayer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShotControllerTest
 *
 * @package App\Tests\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShotControllerTest extends TestCase
{

    public function testFire()
    {
        $controller = new ShotController();
        $match = new Match(new MatchId());
        $match->progressToPhase(Match::PHASE_PLAYING);
        $matchPlayer = new MatchPlayer($match);

        $response = $controller->fire($matchPlayer, new Request([], ['x' => 1, 'y' => 1]));
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testFireNotPlaying()
    {
        $controller = new ShotController();
        $match = new Match(new MatchId());
        $matchPlayer = new MatchPlayer($match);
        $response = $controller->fire($matchPlayer, new Request());

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testFireInvalidCoordinates()
    {
        $controller = new ShotController();
        $match = new Match(new MatchId());
        $match->progressToPhase(Match::PHASE_PLAYING);
        $matchPlayer = new MatchPlayer($match);

        $response = $controller->fire($matchPlayer, new Request());
        $this->assertEquals(400, $response->getStatusCode());

        $response = $controller->fire($matchPlayer, new Request([], ['x' => -1, 'y' => 1]));
        $this->assertEquals(400, $response->getStatusCode());
    }
}
