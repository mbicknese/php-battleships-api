<?php
namespace App\Model\Match;

use App\Uid64\Uid64;

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
     * @param string $uid64
     */
    public function __construct(string $uid64 = null)
    {
        $this->id = $uid64 ?: Uid64::new();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
