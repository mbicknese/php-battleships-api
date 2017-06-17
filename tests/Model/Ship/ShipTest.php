<?php
namespace App\Tests\Model\Ship;

use App\Model\Match\MatchId;
use App\Model\Match\Match;
use App\Model\Ship\Ship;
use App\Model\Ship\ShipCoordinate;
use PHPUnit\Framework\TestCase;

/**
 * Class ShipTest
 *
 * @package App\Tests\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipTest extends TestCase
{
    public function testCollidesWith()
    {
        $ship1 = new Ship(new Match(new MatchId()), 1, [
            new ShipCoordinate(1, 1),
            new ShipCoordinate(1, 2),
            new ShipCoordinate(1, 3),
        ]);
        $ship2 = new Ship(new Match(new MatchId()), 1, [
            new ShipCoordinate(1, 1),
            new ShipCoordinate(2, 1),
            new ShipCoordinate(3, 1),
        ]);
        $this->assertTrue($ship1->collidesWith($ship2));

        $ship3 = new Ship(new Match(new MatchId()), 1, [new ShipCoordinate(2, 4), new ShipCoordinate(2, 5)]);
        $this->assertFalse($ship3->collidesWith($ship1));
    }
}
