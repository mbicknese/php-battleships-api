<?php
namespace App\Tests\Model\Match;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
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

        $this->expectException(ShipsCollideException::class);
        $match->placeShip(0, 1, 1, 3, Vector2::DIRECTION_SOUTH);
    }
}
