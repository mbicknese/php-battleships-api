<?php
namespace App\Model\Match;

/**
 * Class MatchId
 *
 * @package App\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchId
{
    /**
     * @var string
     */
    private $id;

    /**
     * MatchId constructor.
     */
    public function __construct()
    {
        $this->id = uniqid();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
