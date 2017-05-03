<?php
namespace App\Tests\Gameplay;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Model\Ship\Ship;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManager;

/**
 * Class ShipbuilderTest
 *
 * @package App\Tests\Gameplay
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipbuilderTest extends BaseTestCase
{
    /**
     * @var EntityManager
     */
    private static $em;

    public static function setUpBeforeClass()
    {
        self::createClient();
        self::createSchema();
        self::$em = self::$kernel->getContainer()->get('doctrine')->getManager();
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

    public function testBuild()
    {
        $match = new Match(new MatchId());
        /** @var Ship $ship */
        self::$kernel->getContainer()->get('app.shipbuilder')->build($match, 0, 1, 1, 5, 1);

        self::$kernel->getContainer()->get('doctrine.orm.entity_manager')->detach($match);
        $match = self::$kernel->getContainer()->get('app.repository.match')->findOneById($match->id());
        $this->assertCount(1, $match->ships(0));
    }
}
