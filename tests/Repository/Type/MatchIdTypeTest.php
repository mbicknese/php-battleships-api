<?php
namespace App\Tests\Repository\Type;

use App\Model\Match\MatchId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

/**
 * Class MatchIdTypeTest
 *
 * @package App\Tests\Repository\Type
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchIdTypeTest extends TestCase
{
    public function testConvertToPHPValue()
    {
        $matchId = new MatchId();
        $matchIdType = Type::getType('match_id');
        $phpValue = $matchIdType->convertToPHPValue((string) $matchId, $this->getPlatformMock());
        $this->assertInstanceOf(MatchId::class, $phpValue);
        $this->assertEquals($matchId, $phpValue);
    }

    public function testConvertToDatabaseValue()
    {
        $matchId = new MatchId();
        $matchIdType = Type::getType('match_id');
        $this->assertEquals(
            (string) $matchId,
            $matchIdType->convertToDatabaseValue($matchId, $this->getPlatformMock())
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
