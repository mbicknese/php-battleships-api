<?php
namespace App\Controller;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MatchController
 *
 * @package App\Controller
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchController extends Controller
{
    /**
     * @return Response
     * @todo generate location path from routing
     */
    public function joinMatch(): Response
    {
        $match = new Match(new MatchId());

        $data = [
            'id'     => $match->id(),
            'player' => 0,
        ];
        $headers = [
            'Authentication' => 'Not yet implemented',
            'Location'       => sprintf('/match/%s', $match->id()),
        ];

        return new JsonResponse($data, 201, $headers);
    }
}
