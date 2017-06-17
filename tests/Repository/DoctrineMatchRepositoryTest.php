<?php
namespace App\Tests\Repository;

use App\Common\Collections\PlayerCollection;
use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Vector2;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManager;

/**
 * Class DoctrineMatchRepositoryTest
 *
 * @package App\Tests\Repository
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class DoctrineMatchRepositoryTest extends BaseTestCase
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

    public function testPersistence()
    {
        $match = new Match(new MatchId());
        $match->placeShip(1, 1, 1, 2, Vector2::DIRECTION_NORTH);
        $match->placeShip(2, 1, 1, 2, Vector2::DIRECTION_NORTH);
        $this->em->persist($match);
        $this->em->flush();
        $this->em->detach($match);

        $persistedMatch = $this->em->getRepository(Match::class)->findOneBy(['id' => $match->id()]);
        $this->assertEquals($persistedMatch->id(), $match->id());
        $this->assertEquals(15, $match->grid()->height());
        $this->assertEquals(15, $match->grid()->width());
        $this->assertCount(1, $match->ships(1));
        $this->assertCount(1, $match->ships(2));

        $this->em->flush();
    }

    public function testFindOneOpen()
    {
        $emptyResult = $this->em->getRepository(Match::class)->findOneOpen();
        $this->assertNull($emptyResult);

        $match = new Match(new MatchId());

        $this->em->persist($match);
        $this->em->flush();
        $this->em->detach($match);

        /** @var Match $persistedMatch */
        $persistedMatch = $this->em->getRepository(Match::class)->findOneOpen();

        $persistedMatch->join();
        $persistedMatch->join();

        $this->assertEquals($persistedMatch->id(), $match->id());
        $this->em->flush();

        $emptyResult2 = $this->em->getRepository(Match::class)->findOneOpen();
        $this->assertNull($emptyResult2);
    }
}
