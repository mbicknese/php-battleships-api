<?php
namespace App\Repository;

use App\Model\Match\Match;

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
     * @param Match $match
     */
    public function persist(Match $match): void;
}
