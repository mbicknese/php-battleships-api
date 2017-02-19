<?php
namespace App\Tests\Model\Match;

use App\Model\Match\EntityOffGridException;
use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Ship\ShipAlreadyPlacedException;
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
        $this->assertEquals(Match::PHASE_WAITING, $match->phase());
    }

    public function testPlaceShip()
    {
        $match = new Match(new MatchId());
        $ship = $match->placeShip(0, 1, 1, 3, Vector2::DIRECTION_SOUTH);
        $this->assertArraySubset(
            [new Vector2(1, 1), new Vector2(1, 2), new Vector2(1, 3)],
            $ship->coordinates()
        );
        $this->assertCount(3, $ship->coordinates());
    }

    public function testPlaceShipNoCollision()
    {
        $match = new Match(new MatchId());
        $player0Ship1 = $match->placeShip(0, 1, 1, 3, Vector2::DIRECTION_SOUTH);
        $player0Ship2 = $match->placeShip(0, 3, 1, 3, Vector2::DIRECTION_SOUTH);
        $player1Ship1 = $match->placeShip(1, 1, 1, 3, Vector2::DIRECTION_SOUTH);

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
        $match->placeShip(0, 1, 1, 3, Vector2::DIRECTION_SOUTH);
        $match->placeShip(0, 1, 1, 3, Vector2::DIRECTION_SOUTH);
    }
}