<?php
namespace App\Security;

use Firebase\JWT\JWT as JWTBuilder;
use stdClass;

/**
 * Class Jwt
 *
 * @package App\Security
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Jwt
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $algorithm;

    /**
     * Jwt constructor.
     * @param string $key
     * @param string $algorithm (optional) Defaults to 'HS256'
     */
    public function __construct(string $key, string $algorithm = 'HS256')
    {
        $this->key = $key;
        $this->algorithm = $algorithm;
    }

    /**
     * @param array $body
     * @param array $head (optional)
     * @return string
     */
    public function encode(array $body, array $head = []): string
    {
        return JWTBuilder::encode($body, $this->key, $this->algorithm, null, $head);
    }

    /**
     * @param string $jwt
     * @return stdClass
     */
    public function decode(string $jwt): stdClass
    {
        return JWTBuilder::decode($jwt, $this->key, [$this->algorithm]);
    }
}
