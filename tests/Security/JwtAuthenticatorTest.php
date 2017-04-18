<?php
namespace App\Tests\Security;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Security\Jwt;
use App\Security\JwtAuthenticator;
use App\Security\MatchPlayer;
use App\Security\MatchPlayerProvider;
use App\Tests\BaseTestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class JwtAuthenticatorTest
 *
 * @package App\Tests\Security
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class JwtAuthenticatorTest extends BaseTestCase
{
    public function setUp()
    {
        $this->createClient();
        $this->createSchema();
    }

    public function testGetCredentials()
    {
        $request = new Request([], [], [], [], [], ['HTTP_Authorization' => 'bearer abc.abc.abc']);
        $jwtAuthenticator = new JwtAuthenticator(new Jwt('test'));

        $token = $jwtAuthenticator->getCredentials($request);
        $this->assertEquals('abc.abc.abc', $token);
    }

    public function testOnAuthenticationFailureUsernameNotFoundException()
    {
        $client = $this->createClient();
        $client->request('POST', '/ship');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());

        $client = $this->createClient();
        $client->request('POST', '/ship', [], [], ['HTTP_Authorization' => 'bearer abc.abc.abc']);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testGetUser()
    {
        $jwt = new Jwt('test');
        $jwtAuthenticator = new JwtAuthenticator($jwt);
        $matchPlayer = new MatchPlayer(new Match(new MatchId()));
        /** @var PHPUnit_Framework_MockObject_MockObject|MatchPlayerProvider $matchPlayerProvider */
        $matchPlayerProvider = $this
            ->getMockBuilder(MatchPlayerProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
        $matchPlayerProvider->method('loadUserByUsername')->willReturn($matchPlayer);


        $returnedMatchPlayer = $jwtAuthenticator->getUser(
            $jwt->encode(['id' => $matchPlayer->getUsername(), 'player' => 1]),
            $matchPlayerProvider
        );
        $this->assertEquals(1, $returnedMatchPlayer->getSequence());
        $this->assertEquals($matchPlayer->getUsername(), $returnedMatchPlayer->getUsername());
    }
}
