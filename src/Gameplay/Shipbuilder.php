<?php
namespace App\Gameplay;

use App\Model\Match\Match;
use App\Model\Vector2;
use App\Repository\MatchRepository;

/**
 * Class Shipbuilder
 *
 * @package App\Gameplay
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Shipbuilder
{
    /**
     * @var MatchRepository
     */
    private $matchRepository;

    /**
     * Shipbuilder constructor.
     * @param MatchRepository $matchRepository
     */
    public function __construct(MatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    /**
     * @param Match $match
     * @param int $sequence
     * @param int $startX
     * @param int $startY
     * @param int $endX
     * @param int $endY
     */
    public function build(Match $match, int $sequence, int $startX, int $startY, int $endX, int $endY): void
    {
        $direction = new Vector2($endX - $startX, $endY - $startY);
        $match->placeShip($sequence, $startX, $startY, $direction->magnitude(), $direction->singleAxisDirection());

        $this->matchRepository->persist($match);
    }
}
