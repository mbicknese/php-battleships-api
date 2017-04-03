<?php
namespace App\Model\Match;

/**
 * Class Player
 *
 * @package App\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Player
{
    /**
     * @var Match
     */
    private $match;
    /**
     * @var int
     */
    private $sequence;

    /**
     * Player constructor.
     * @param Match $match
     * @param int $sequence
     */
    public function __construct(Match $match, int $sequence)
    {
        $this->match = $match;
        $this->sequence = $sequence;
    }
}
