<?php
namespace App\Tests\Uid64;

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
}
