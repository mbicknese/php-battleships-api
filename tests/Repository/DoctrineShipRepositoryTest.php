<?php
namespace App\Tests\Repository;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Ship\Ship;
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
        self::createClient();
        self::createSchema();
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testPersistShip(): void
    {
        $match = new Match(new MatchId());
        $ship = $match->placeShip(0, 1, 1, 2, Vector2::DIRECTION_SOUTH);

        $this->em->persist($match);
        $this->em->flush();
        $this->em->detach($match);

        $ships = $this->em->getRepository(Ship::class)->findAll();
        $persistedMatch = $this->em->getRepository(Match::class)->findOneBy(['id' => $match->id()]);

        $this->assertCount(1, $ships);
        $this->assertCount(1, $match->ships());
        $this->assertCount(1, $persistedMatch->ships());
        $this->assertEquals($persistedMatch->ships()[0], $ship);
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
