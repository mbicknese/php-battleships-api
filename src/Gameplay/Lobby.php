<?php
namespace App\Gameplay;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Repository\MatchRepository;

/**
 * Class Lobby
 *
 * @package App\Gameplay
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Lobby
{
    /**
     * @var MatchRepository
     */
    protected $matchRepository;

    /**
     * Lobby constructor.
     * @param MatchRepository $matchRepository
     */
    public function __construct(MatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    /**
     * Joins any match with an open slot
     *
     * A new match is started if all matches are fully occupied. This creates slots for both the requesting player as
     * well as any opponents joining the match later on.
     *
     * @return Match
     */
    public function joinOpenMatch(): Match
    {
        $match = $this->matchRepository->findOneOpen();
        if (! $match) {
            $match = new Match(new MatchId());
        }

        $match->join();
        $this->matchRepository->persist($match);
        return $match;
    }
}
