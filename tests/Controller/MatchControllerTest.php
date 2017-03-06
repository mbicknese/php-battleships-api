<?php
namespace App\Tests\Controller;

use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManager;

/**
 * Class MatchControllerTest
 *
 * @package App\Tests\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchControllerTest extends BaseTestCase
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

    public function testJoinMatch()
    {
        $matchController = self::$kernel->getContainer()->get('app.controller.match');

        $response = $matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(1, $content['player']);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('authentication', $response->headers->all());
        $this->assertArrayHasKey('location', $response->headers->all());

        $response = $matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(2, $content['player']);

        $response = $matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(1, $content['player']);


    }
}
