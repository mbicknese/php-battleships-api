<?php

namespace App\Model\Ship;

use App\Model\Match\Match;

/**
 * Class PlayerShip
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipPlacement
{
    /**
     * @var Match
     */
    private $match;
    /**
     * @var int
     */
    private $player;
    /**
     * @var int
     */
    private $sequence;

    /**
     * PlayerShip constructor.
     * @param Match $match
     * @param int $player
     * @param int $sequence
     */
    public function __construct(Match $match, int $player, int $sequence)
    {
        $this->match = $match;
        $this->player = $player;
        $this->sequence = $sequence;
    }

    /**
     * @return Match
     */
    public function match(): Match
    {
        return $this->match;
    }

    /**
     * @return int
     */
    public function player(): int
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function sequence(): int
    {
        return $this->sequence;
    }
}
