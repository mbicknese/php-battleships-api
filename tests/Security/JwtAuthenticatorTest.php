<?php
namespace App\Tests\Security;

use App\Security\Jwt;
use App\Security\JwtAuthenticator;
use App\Tests\BaseTestCase;
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
}
