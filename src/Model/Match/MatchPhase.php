<?php
namespace App\Model\Match;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Class MatchPhase
 *
 * @package App\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchPhase
{
    /**
     * @var Match
     */
    private $match;
    /**
     * @var DateTimeInterface;
     */
    private $startedAt;
    /**
     * @var int
     */
    private $phase;

    /**
     * MatchPhase constructor.
     * @param Match $match
     * @param int $phase
     */
    public function __construct(Match $match, int $phase)
    {
        $this->match = $match;
        $this->startedAt = new DateTimeImmutable();
        $this->phase = $phase;
    }

    /**
     * @return int
     */
    public function phase(): int
    {
        return $this->phase;
    }
}
