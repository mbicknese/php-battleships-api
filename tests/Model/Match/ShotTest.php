<?php
namespace App\Tests\Model\Match;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Ship\ShipCoordinate;
use App\Model\Shot\Shot;
use App\Model\Ship\Ship;
use PHPUnit\Framework\TestCase;

class ShotTest extends TestCase
{
    public function testDoesHit()
    {
        $match = new Match(new MatchId());
        $ship = new Ship($match, 1, [new ShipCoordinate(1, 1)]);
        $this->assertTrue((new Shot(1, 1, $match, 2))->doesHit($ship));
        $this->assertFalse((new Shot(1, 2, $match, 2))->doesHit($ship));
        $this->assertFalse((new Shot(2, 1, $match, 2))->doesHit($ship));
        $this->assertFalse((new Shot(-1, -1, $match, 2))->doesHit($ship));
    }

    public function testHits()
    {
        $match = new Match(new MatchId());
        $ship = new Ship($match, 1, [new ShipCoordinate(1, 1), new ShipCoordinate(1, 2)]);
        $shot1 = new Shot(1, 1, $match, 2);
        $shot2 = new Shot(1, 2, $match, 2);
        $shot3 = new Shot(1, 2, $match, 2);

        $shot1->hits($ship);
        $shot2->hits($ship, [$shot1]);
        $shot3->hits($ship, [$shot1, $shot2]);

        $this->assertTrue($shot1->hasHit());
        $this->assertFalse($shot1->hasSunk());
        $this->assertTrue($shot2->hasHit());
        $this->assertTrue($shot2->hasSunk());
        $this->assertTrue($shot3->hasHit());
        $this->assertFalse($shot3->hasSunk());
    }
}
