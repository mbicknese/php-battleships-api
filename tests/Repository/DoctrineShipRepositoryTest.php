<?php
namespace App\Tests\Repository;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Ship\Ship;
use App\Model\Ship\ShipCoordinate;
use App\Model\Vector2;
use App\Tests\BaseTestCase;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;

/**
 * Class DoctrineShipRepositoryTest
 *
 * @package App\Tests\Repository
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class DoctrineShipRepositoryTest extends BaseTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    public function setUp()
    {
        self::bootKernel();
        self::createSchema();
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testPersistShip(): void
    {
        $match = new Match(new MatchId());
        $ship = $match->placeShip(0, 1, 1, 2, Vector2::DIRECTION_SOUTH);
        $this->assertEquals(1, $ship->sequence());
        $this->assertEquals(2, $match->placeShip(0, 3, 1, 3, Vector2::DIRECTION_SOUTH)->sequence());

        $this->em->persist($match);
        $this->em->flush();

        $this->assertEquals(3, $match->placeShip(0, 5, 1, 4, Vector2::DIRECTION_SOUTH)->sequence());
        $this->em->flush();

        $this->em->detach($match);

        $ships = $this->em->getRepository(Ship::class)->findAll();
        $persistedMatch = $this->em->getRepository(Match::class)->findOneBy(['id' => $match->id()]);

        $this->assertCount(3, $ships);
        $this->assertCount(3, $match->ships());
        $this->assertCount(3, $persistedMatch->ships());
        $this->assertEquals($ship->id(), $persistedMatch->ships()[0]->id());
        $this->assertTrue((new ShipCoordinate(1, 1, $ship))->equals($persistedMatch->ships()[0]->coordinates()[0]));
    }

    public function testPersistShipParallel(): void
    {
        $match = new Match(new MatchId());

        $this->em->persist($match);
        $this->em->flush();
        $this->em->detach($match);

        $persistedMatch = $this->em->getRepository(Match::class)->findOneBy(['id' => $match->id()]);
        $persistedMatch->placeShip(0, 1, 1, 2, Vector2::DIRECTION_EAST);

        $this->assertCount(0, $match->ships());
        $this->assertCount(1, $persistedMatch->ships());

        $match->placeShip(0, 1, 3, 2, Vector2::DIRECTION_EAST);

        $this->assertCount(1, $match->ships());
        $this->assertCount(1, $persistedMatch->ships());

        $this->em->persist($match);
        $this->em->persist($persistedMatch);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->em->flush();
    }
}
