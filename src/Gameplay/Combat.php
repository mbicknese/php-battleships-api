<?php
namespace App\Gameplay;

use App\Model\Match\Match;
use App\Model\Shot\Shot;
use App\Repository\MatchRepository;

/**
 * Class Combat
 *
 * @package App\Gameplay
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Combat
{
    /**
     * @var MatchRepository
     */
    private $matchRepository;

    /**
     * Combat constructor.
     * @param MatchRepository $matchRepository
     */
    public function __construct(MatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    /**
     * @param Match $match
     * @param int $x
     * @param int $y
     * @param int $player
     * @return Shot
     */
    public function fireShot(Match $match, int $x, int $y, int $player): Shot
    {
        $shot = $match->fireShot($player, $x, $y);
        if ($shot->hasSunk() && self::countShipsSunk($match, $player) == count($match->shipSet())) {
            $match->progressToPhase(Match::PHASE_FINISHED);
        }

        $this->matchRepository->persist($match);

        return $shot;
    }

    /**
     * Counts the amount of ships sunk in a match
     *
     * @param Match $match
     * @param int|null $player (optional) Limit to only sunk ships for given player
     * @return int
     */
    public static function countShipsSunk(Match $match, int $player = null): int
    {
        return count(array_filter($match->shots($player), function (Shot $shot) {
            return $shot->hasSunk();
        }));
    }
}
