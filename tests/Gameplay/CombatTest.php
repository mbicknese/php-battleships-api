<?php
namespace App\Tests\Gameplay;

use App\Gameplay\Combat;
use App\Model\Grid;
use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Vector2;
use App\Tests\BaseTestCase;

/**
 * Class CombatTest
 *
 * @package App\Tests\Gameplay
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class CombatTest extends BaseTestCase
{
    public function testCountShipsSunk()
    {
        $match = new Match(new MatchId());
        $match->placeShip(1, 1, 1, 2, Vector2::DIRECTION_EAST);
        $this->assertTrue(($match->fireShot(2, 1, 1))->hasHit());

        $shot = $match->fireShot(2, 2, 1);
        $this->assertTrue($shot->hasHit());
        $this->assertTrue($shot->hasSunk());
        $this->assertEquals(1, Combat::countShipsSunk($match));

        $match->placeShip(2, 1, 1, 2, Vector2::DIRECTION_EAST);
        $match->fireShot(1, 1, 1);
        $match->fireShot(1, 2, 1);

        $this->assertEquals(2, Combat::countShipsSunk($match));
        $this->assertEquals(1, Combat::countShipsSunk($match, 1));
        $this->assertEquals(1, Combat::countShipsSunk($match, 2));
    }

    public function testFireShot()
    {
        self::bootKernel();
        self::createSchema();
        /** @var Combat $combat */
        $combat = self::$kernel->getContainer()->get('app.combat');
        $match = new Match(new MatchId(), new Grid(15, 15), [2]);
        $match->placeShip(2, 1, 1, 2, Vector2::DIRECTION_SOUTH);

        $combat->fireShot($match, 1, 1, 1);
        $this->assertTrue($combat->fireShot($match, 1, 2, 1)->hasSunk());

        $this->assertEquals(Match::PHASE_FINISHED, $match->phase()->phase());

        self::$kernel->getContainer()->get('doctrine.orm.entity_manager')->detach($match);
        /** @var Match $match */
        $match = self::$kernel->getContainer()->get('app.repository.match')->findOneById($match->id());
        $this->assertCount(2, $match->shots());
    }
}
