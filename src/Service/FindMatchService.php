<?php
namespace App\Service;

use App\Model\Match\Match;
use App\Model\Match\MatchId;
use App\Repository\MatchRepository;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class FindMatchService
 *
 * @package App\Service
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class FindMatchService
{
    /**
     * @var MatchRepository
     */
    private $matchRepository;

    /**
     * FindMatchService constructor.
     * @param MatchRepository $matchRepository
     */
    public function __construct(MatchRepository $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    /**
     * @param MatchId $matchId
     * @return Match
     * @throws EntityNotFoundException
     */
    public function execute(MatchId $matchId): Match
    {
        $match = $this->matchRepository->findOneById($matchId);

        if (! $match) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Match::class, [(string)$matchId]);
        }

        return $match;
    }
}
