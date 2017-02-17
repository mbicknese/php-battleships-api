<?php
namespace App\Tests\Model;

use App\Model\Vector2;
use PHPUnit\Framework\TestCase;

/**
 * Class Vector2Test
 *
 * @package App\Tests\Model
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Vector2Test extends TestCase
{
    public function testTouch()
    {
        $base = new Vector2(0, 0);
        $this->assertTrue($base->touches($base));
        $this->assertTrue($base->touches($base->move(Vector2::DIRECTION_NORTH)));
        $this->assertTrue($base->touches($base->move(Vector2::DIRECTION_EAST)));
        $this->assertTrue($base->touches($base->move(Vector2::DIRECTION_SOUTH)));
        $this->assertTrue($base->touches($base->move(Vector2::DIRECTION_WEST)));

        $this->assertFalse($base->touches($base->move(Vector2::DIRECTION_WEST)->move(Vector2::DIRECTION_NORTH)));
        $this->assertFalse($base->touches($base->move(Vector2::DIRECTION_WEST)->move(Vector2::DIRECTION_SOUTH)));
        $this->assertFalse($base->touches($base->move(Vector2::DIRECTION_WEST)->move(Vector2::DIRECTION_WEST)));
    }
}
