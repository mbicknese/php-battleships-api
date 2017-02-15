<?php
namespace App\Tests\Model\Match;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use PHPUnit\Framework\TestCase;

/**
 * Class MatchTest
 *
 * @package App\Tests\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchTest extends TestCase
{
    public function testConstructor(): void
    {
        $match = new Match(new MatchId());

        $this->assertInstanceOf(Match::class, $match);
        $this->assertInstanceOf(MatchId::class, $match->id());
        $this->assertEquals(Match::PHASE_WAITING, $match->phase());
    }
}
