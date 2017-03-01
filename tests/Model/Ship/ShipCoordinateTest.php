<?php
namespace App\Tests\Model\Ship;

use App\Model\Ship\CoordinateAlreadyAttachedException;
use App\Model\Ship\Ship;
use App\Model\Ship\ShipCoordinate;
use PHPUnit\Framework\TestCase;

/**
 * Class ShipCoordinateTest
 *
 * @package App\Tests\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipCoordinateTest extends TestCase
{
    public function testAttachToShip()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Ship $ship */
        $ship = $this->getMockBuilder(Ship::class)->disableOriginalConstructor()->getMock();
        $coordinateWithout = new ShipCoordinate(1, 1);
        $coordinateWith = new ShipCoordinate(1, 1, $ship);
        $coordinateWithout->attachToShip($ship);

        $this->assertEquals($coordinateWithout, $coordinateWith);
    }

    public function testAttachToShipTwice()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Ship $ship */
        $ship = $this->getMockBuilder(Ship::class)->disableOriginalConstructor()->getMock();
        $coordinate = new ShipCoordinate(1, 1);
        $coordinate->attachToShip($ship);

        $this->expectException(CoordinateAlreadyAttachedException::class);
        $coordinate->attachToShip($ship);
    }
}
