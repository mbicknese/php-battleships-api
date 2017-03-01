<?php
namespace App\Tests\Repository\Type;

use App\Model\Ship\ShipId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

/**
 * Class ShipIdTypeTest
 *
 * @package App\Tests\Repository\Type
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipIdTypeTest extends TestCase
{
    public function testConvertToPHPValue()
    {
        $shipId = new ShipId();
        $shipIdType = Type::getType('ship_id');
        $phpValue = $shipIdType->convertToPHPValue((string) $shipId, $this->getPlatformMock());
        $this->assertInstanceOf(ShipId::class, $phpValue);
        $this->assertEquals($shipId, $phpValue);
    }

    public function testConvertToDatabaseValue()
    {
        $shipId = new ShipId();
        $shipIdType = Type::getType('ship_id');
        $this->assertEquals(
            (string) $shipId,
            $shipIdType->convertToDatabaseValue($shipId, $this->getPlatformMock())
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractPlatform
     */
    private function getPlatformMock()
    {
        return $this
            ->getMockBuilder(AbstractPlatform::class)
            ->getMockForAbstractClass();
    }
}
