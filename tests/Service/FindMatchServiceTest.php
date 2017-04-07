<?php
namespace App\Tests\Service;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Service\FindMatchService;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class FindMatchServiceTest
 *
 * @package App\Tests\Service
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class FindMatchServiceTest extends BaseTestCase
{
    /**
     * @var EntityManager
     */
    private static $em;

    /**
     * @var FindMatchService
     */
    private static $findMatchService;

    public static function setUpBeforeClass()
    {
        self::createClient();
        self::createSchema();
        self::$em = self::$kernel->getContainer()->get('doctrine')->getManager();
        self::$findMatchService = self::$kernel->getContainer()->get('app.findMatch');
    }

    public static function tearDownAfterClass()
    {
        parent::ensureKernelShutdown();
        parent::tearDownAfterClass();
    }

    public function tearDown()
    {
        // Ensure nothing happens
    }


    public function testExecute()
    {
        $match = new Match(new MatchId());
        self::$em->persist($match);
        self::$em->flush();
        self::$em->detach($match);

        $persistedMatch = self::$findMatchService->execute($match->id());

        $this->assertEquals($match->id(), $persistedMatch->id());
    }

    public function testExecuteThrowsNotFoundException()
    {
        $this->expectException(EntityNotFoundException::class);
        self::$findMatchService->execute(new MatchId());
    }
}
