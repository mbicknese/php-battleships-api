<?php
namespace App\Tests\Controller;

use App\Controller\MatchController;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;

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
        /** @var MatchController $matchController */
        $matchController = self::$kernel->getContainer()->get('app.controller.match');

        $response = $matchController->joinMatch();
        $content = json_decode($response->getContent(), true);
        $jwtContent = JWT::decode(
            $response->headers->get('authentication'),
            self::$kernel->getContainer()->getParameter('env(APP_SECRET)'),
            ['HS256']
        );

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('location', $response->headers->all());
        $this->assertEquals(1, $content['player']);
        $this->assertEquals($content['player'], $jwtContent->player);
        $this->assertInternalType('string', $jwtContent->id);

        $response = $matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(2, $content['player']);

        $response = $matchController->joinMatch();
        $content = json_decode($response->getContent(), true);

        $this->assertEquals(1, $content['player']);
    }
}
