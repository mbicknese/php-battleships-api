<?php
namespace App\Tests\Uid64;

use App\Uid64\InvalidUid64Exception;
use App\Uid64\Uid64;
use PHPUnit\Framework\TestCase;

/**
 * Class Uid64Test
 *
 * @package App\Tests\Uid64
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Uid64Test extends TestCase
{
    public function testNew()
    {
        $ids = [];
        for ($i = 0; $i < 1000; ++$i) {
            $id = Uid64::new();
            $this->assertNotContains($id, $ids);
            $ids[] = $id;
        }

        $this->assertInternalType('string', $id);
    }

    public function testIsUid64()
    {
        $this->assertTrue(Uid64::isUid64('9223372036854775807'), 'Max int value is a valid UID64');
        $this->assertTrue(Uid64::isUid64('0'), 'zero (0) is a valid UID64');
        $this->assertFalse(Uid64::isUid64('9223372036854775808'), 'One more than max in is not a valid UID64');
        $this->assertFalse(Uid64::isUid64('-1'), '-1 is not a valid UID64');
        $this->assertFalse(Uid64::isUid64('a'), 'a letter is not a valid UID64');
    }

    public function testToText()
    {
        $this->assertEquals('0', Uid64::toText('0'));
        $this->assertEquals('1y2p0ij32e8e7', Uid64::toText('9223372036854775807'));
        $this->assertEquals('7oxrx2x69ao', Uid64::toText('28125832510328208'));

        $this->expectException(InvalidUid64Exception::class);
        Uid64::toText('-1');
    }
}
