<?php
namespace App\Tests\Model\Match;

use App\Model\Grid;
use App\Model\Match\EntityOffGridException;
use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Match\NoSlotsAvailableException;
use App\Model\Ship\ShipAlreadyPlacedException;
use App\Model\Ship\ShipCoordinate;
use App\Model\Ship\ShipsCollideException;
use App\Model\Vector2;
use PHPUnit\Framework\TestCase;

/**
 * Class MatchTest
 *
 * @package App\Tests\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchTest extends TestCase
{
    public function testConstructor(): void
    {
        $match = new Match(new MatchId());

        $this->assertInstanceOf(Match::class, $match);
        $this->assertInstanceOf(MatchId::class, $match->id());
        $this->assertEquals(Match::PHASE_WAITING, $match->phase()->phase());
    }

    public function testPlaceShip()
    {
        $match = new Match(new MatchId(), new Grid(15, 15), [2, 3]);
        $ship1 = $match->placeShip(1, 1, 1, 3, Vector2::DIRECTION_SOUTH);
        $ship2 = $match->placeShip(1, 3, 1, 2, Vector2::DIRECTION_EAST);
        $this->assertArraySubset(
            [new ShipCoordinate(1, 1, $ship1), new ShipCoordinate(1, 2, $ship1), new ShipCoordinate(1, 3, $ship1)],
            $ship1->coordinates()
        );
        $this->assertArraySubset(
            [new ShipCoordinate(3, 1, $ship2), new ShipCoordinate(4, 1, $ship2)],
            $ship2->coordinates()
        );
        $this->assertCount(3, $ship1->coordinates());
        $this->assertCount(2, $ship2->coordinates());
        $this->assertEquals(1, $ship1->sequence());
        $this->assertEquals(2, $ship2->sequence());
    }

    public function testPlaceShipNoCollision()
    {
        $match = new Match(new MatchId());
        $player0Ship1 = $match->placeShip(1, 1, 1, 3, Vector2::DIRECTION_SOUTH);
        $player0Ship2 = $match->placeShip(1, 3, 1, 3, Vector2::DIRECTION_SOUTH);
        $player1Ship1 = $match->placeShip(2, 1, 1, 3, Vector2::DIRECTION_SOUTH);

        $this->assertFalse($player0Ship1->collidesWith($player0Ship2));
        $this->assertTrue($player0Ship1->collidesWith($player1Ship1));
    }

    public function testPlaceShipAlreadyPlaced()
    {
        $this->expectException(ShipAlreadyPlacedException::class);

        $match = new Match(new MatchId());
        $match->placeShip(1, 5, 5, 2, Vector2::DIRECTION_SOUTH);
        $match->placeShip(1, 5, 5, 2, Vector2::DIRECTION_SOUTH);
    }

    public function testPlaceShipOffGrid()
    {
        $this->expectException(EntityOffGridException::class);

        $match = new Match(new MatchId());
        $match->placeShip(1, 50, 50, 2, Vector2::DIRECTION_SOUTH);
    }

    public function testPlaceShipCollision()
    {
        $this->expectException(ShipsCollideException::class);

        $match = new Match(new MatchId());
        $match->placeShip(1, 1, 1, 3, Vector2::DIRECTION_SOUTH);
        $match->placeShip(1, 1, 1, 3, Vector2::DIRECTION_SOUTH);
    }

    public function testJoinTooMany()
    {
        $match = new Match(new MatchId());
        $match->join();
        $match->join();

        $this->expectException(NoSlotsAvailableException::class);
        $match->join();
    }

    public function testProgressToPhase()
    {
        $match = new Match(new MatchId());
        $match->progressToPhase(Match::PHASE_FINISHED);
        $this->assertEquals(Match::PHASE_FINISHED, $match->phase()->phase());
    }
}
