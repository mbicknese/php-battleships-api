<?php
namespace App\Tests\Repository;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
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
        self::createClient();
        self::createSchema();
        $this->em = self::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testPersistence()
    {
        $match = new Match(new MatchId());
        $this->em->persist($match);
        $this->em->flush();
        $this->em->detach($match);

        $persistedMatch = $this->em->getRepository(Match::class)->findOneBy(['id' => $match->id()]);
        $this->assertEquals($persistedMatch->id(), $match->id());
        $this->assertEquals(15, $match->grid()->height());
        $this->assertEquals(15, $match->grid()->width());
    }

    public function testFindOneOpen()
    {
        $emptyResult = $this->em->getRepository(Match::class)->findOneOpen();
        $this->assertNull($emptyResult);

        $match = new Match(new MatchId());

        $this->em->persist($match);
        $this->em->flush();
        $this->em->detach($match);

        $persistedMatch = $this->em->getRepository(Match::class)->findOneOpen();
        $this->assertEquals($persistedMatch->id(), $match->id());

        $persistedMatch->join();
        $persistedMatch->join();

        $this->em->flush();

        $emptyResult2 = $this->em->getRepository(Match::class)->findOneOpen();
        $this->assertNull($emptyResult2);
    }
}
