<?php
namespace App\Controller;

use App\Gameplay\Lobby;
use App\Security\Jwt;
use App\Uid64\Uid64;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MatchController
 *
 * @package App\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchController
{
    /**
     * @var Lobby
     */
    private $lobby;
    /**
     * @var Jwt
     */
    private $jwt;

    /**
     * MatchController constructor.
     * @param Lobby $lobby
     * @param Jwt $jwt
     */
    public function __construct(Lobby $lobby, Jwt $jwt)
    {
        $this->lobby = $lobby;
        $this->jwt = $jwt;
    }

    /**
     * @return Response
     */
    public function joinMatch(): Response
    {
        $match = $this->lobby->joinOpenMatch();
        $id = Uid64::toText($match->id());

        $data = [
            'id'     => $id,
            'player' => count($match->players()),
        ];
        $headers = [
            'Authentication' => $this->jwt->encode($data),
            'Location'       => sprintf('/match/%s', $id),
        ];

        return new JsonResponse($data, 201, $headers);
    }
}
