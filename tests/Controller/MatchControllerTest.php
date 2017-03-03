<?php
namespace App\Tests\Controller;

use App\Controller\MatchController;
use PHPUnit\Framework\TestCase;

/**
 * Class MatchControllerTest
 *
 * @package App\Tests\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchControllerTest extends TestCase
{
    public function testJoinMatch()
    {
        $matchController = new MatchController();
        $response = $matchController->joinMatch();

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('player', $content);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('authentication', $response->headers->all());
    }
}
