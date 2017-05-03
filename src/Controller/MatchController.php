<?php
namespace App\Controller;

use App\Gameplay\Lobby;
use App\Model\Match\MatchId;
use App\Security\Jwt;
use App\Service\FindMatchService;
use App\Uid64\InvalidUid64TextException;
use App\Uid64\Uid64;
use Doctrine\ORM\EntityNotFoundException;
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
     * @var findMatchService
     */
    private $findMatchService;

    /**
     * MatchController constructor.
     * @param Lobby $lobby
     * @param Jwt $jwt
     * @param findMatchService $findMatchService
     */
    public function __construct(Lobby $lobby, Jwt $jwt, findMatchService $findMatchService)
    {
        $this->lobby = $lobby;
        $this->jwt = $jwt;
        $this->findMatchService = $findMatchService;
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
            'Authorization' => $this->jwt->encode($data),
            'Location'      => sprintf('/match/%s', $id),
        ];

        return new JsonResponse($data, 201, $headers);
    }

    /**
     * @param string $matchId
     * @return Response
     */
    public function displayMatch(string $matchId): Response
    {
        try {
            $match = $this->findMatchService->execute(new MatchId(Uid64::fromText($matchId)));
        } catch (InvalidUid64TextException $invalidUid64TextException) {
            return new Response('', 404);
        } catch (EntityNotFoundException $entityNotFoundException) {
            return new Response('', 404);
        }

        return new JsonResponse([
            'phase'          => $match->phase()->phase(),
            'current_player' => 0, // Yet to be implemented
            'ships'          => $match->shipSet(),
            'grid'           => [$match->grid()->width(), $match->grid()->height()],
            'shots'          => [], // Yet to be implemented
        ]);
    }
}
