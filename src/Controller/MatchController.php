<?php
namespace App\Controller;

use App\Gameplay\Lobby;
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
     * MatchController constructor.
     * @param Lobby $lobby
     */
    public function __construct(Lobby $lobby)
    {
        $this->lobby = $lobby;
    }

    /**
     * @return Response
     */
    public function joinMatch(): Response
    {
        $match = $this->lobby->joinOpenMatch();

        $data = [
            'id'     => $match->id(),
            'player' => count($match->players()),
        ];
        $headers = [
            'Authentication' => 'Not yet implemented',
            'Location'       => sprintf('/match/%s', Uid64::toText($match->id())),
        ];

        return new JsonResponse($data, 201, $headers);
    }
}
