<?php
namespace App\Repository;

use App\Model\Match\Match;
use App\Model\Match\MatchId;

/**
 * Interface MatchRepository
 * @package App\Repository
 */
interface MatchRepository
{
    /**
     * @return Match|null
     */
    public function findOneOpen(): ?Match;

    /**
     * @param MatchId $matchId
     * @return Match|null
     */
    public function findOneById(MatchId $matchId): ?Match;

    /**
     * @param Match $match
     */
    public function persist(Match $match): void;
}
