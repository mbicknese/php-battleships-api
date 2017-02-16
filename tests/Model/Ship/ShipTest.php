<?php
namespace App\Tests\Model\Ship;

use App\Model\Ship\Ship;
use App\Model\Ship\ShipAlreadySunkException;
use PHPUnit\Framework\TestCase;

/**
 * Class ShipTest
 *
 * @package App\Tests\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipTest extends TestCase
{
    public function testHitPoints()
    {
        $hitPoints = 2;

        $ship = new Ship($hitPoints);
        $this->assertEquals($hitPoints, $ship->hitPoints());

        $ship->hit();
        $this->assertEquals($hitPoints - 1, $ship->hitPoints());

        $ship->hit();
        $this->assertEquals(0, $ship->hitPoints());
        $this->assertTrue($ship->hasSunk(), 'Expected ship with 2 hit points to have sunk after two hits.');

        $this->expectException(ShipAlreadySunkException::class);
        $ship->hit();
    }
}
