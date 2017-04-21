<?php
namespace App\Tests\Model;

use App\Model\Grid;
use App\Model\Vector2;
use PharIo\Version\VersionConstraintParser;
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

    public function testIsOffGrid()
    {
        $this->assertFalse((new Vector2(0, 0))->isOffGrid(new Grid(5, 5)), '[0, 0] is determined as off the grid');
        $this->assertFalse((new Vector2(5, 5))->isOffGrid(new Grid(5, 5)), '[5, 5] is determined as off a [5, 5] grid');

        $this->assertTrue((new Vector2(-1, 0))->isOffGrid(new Grid(5, 5)), '[-1, 0] is determined as on grid');
        $this->assertTrue((new Vector2(0, -1))->isOffGrid(new Grid(5, 5)), '[0, -1] is determined as on grid');
        $this->assertTrue((new Vector2(-1, -1))->isOffGrid(new Grid(5, 5)), '[-1, -1] is determined as on grid');
        $this->assertTrue((new Vector2(6, 5))->isOffGrid(new Grid(5, 5)), '[6, 5] is determined as on a [5, 5] grid');
        $this->assertTrue((new Vector2(5, 6))->isOffGrid(new Grid(5, 5)), '[5, 6] is determined as on a [5, 5] grid');
        $this->assertTrue((new Vector2(6, 6))->isOffGrid(new Grid(5, 5)), '[6, 6] is determined as on a [5, 5] grid');
    }

    public function testEquals()
    {
        $vector2 = new Vector2(5, 5);
        $this->assertTrue($vector2->equals($vector2), 'it did not equal itself');
        $this->assertTrue($vector2->equals(new Vector2(5, 5)), 'it did not equal the same location');
        $this->assertFalse($vector2->equals(new Vector2(5, 6)), 'it equaled 1 down');
        $this->assertFalse($vector2->equals(new Vector2(5, 4)), 'it equaled 1 up');
        $this->assertFalse($vector2->equals(new Vector2(4, 5)), 'it equaled 1 to the left');
        $this->assertFalse($vector2->equals(new Vector2(6, 5)), 'it equaled 1 to the right');
        $this->assertFalse($vector2->equals(new Vector2(-5, -5)), 'it equaled its negative counterpart');
    }

    public function testDiff()
    {
        $this->assertEquals(
            new Vector2(1, 2),
            Vector2::diff(new Vector2(2, 3), new Vector2(3, 5))
        );
        $this->assertEquals(
            new Vector2(-2, -1),
            Vector2::diff(new Vector2(5, 3), new Vector2(3, 2))
        );
    }

    public function testSingleAxisDirection()
    {
    }

    public function testNormalize()
    {
        $this->assertEquals(new Vector2(0, 0), (new Vector2(0, 0))->normalize());
        $this->assertEquals(new Vector2(1, 0), (new Vector2(5, 0))->normalize());
        $this->assertEquals(new Vector2(0, 1), (new Vector2(0, 5))->normalize());
        $this->assertEquals(new Vector2(-1, 0), (new Vector2(-5, 0))->normalize());
        $this->assertEquals(new Vector2(0, -1), (new Vector2(0, -5))->normalize());
    }

    public function testMagnitude()
    {
        $this->assertEquals(0, (new Vector2(0, 0))->magnitude());
        $this->assertEquals(2, (new Vector2(2, 0))->magnitude());
        $this->assertEquals(2, (new Vector2(0, 2))->magnitude());
        $this->assertEquals(2, (new Vector2(-2, 0))->magnitude());
        $this->assertEquals(2, (new Vector2(0, -2))->magnitude());
        $this->assertEquals(5, (new Vector2(3, 4))->magnitude());
        $this->assertEquals(2.828, round((new Vector2(-2, -2))->magnitude(), 3));
    }
}
