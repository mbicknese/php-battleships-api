<?php
namespace App\Model\Ship;

use App\Model\Match\Match;
use App\Model\Vector2;

/**
 * Class Ship
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Ship
{
    /**
     * @var Match
     */
    protected $match;
    /**
     * @var int
     */
    private $sequence;
    /**
     * @var iterable
     */
    protected $coordinates;
    /**
     * @var int
     */
    protected $player;

    /**
     * Ship constructor.
     * @param Match $match
     * @param int $sequence
     * @param iterable|Vector2[] $coordinates
     * @param int $player (optional)
     */
    public function __construct(Match $match, int $sequence, iterable $coordinates, int $player = 0)
    {
        $this->match = $match;
        $this->sequence = $sequence;
        $this->coordinates = $coordinates;
        $this->player = $player;
    }

    /**
     * @return iterable|Vector2[]
     */
    public function coordinates(): iterable
    {
        return $this->coordinates;
    }

    /**
     * @return int
     */
    public function hitPoints(): int
    {
        return count($this->coordinates);
    }

    /**
     * @return int
     */
    public function sequence(): int
    {
        return $this->sequence;
    }

    /**
     * @param Ship $other
     * @return bool
     */
    public function collidesWith(Ship $other): bool
    {
        foreach ($this->coordinates() as $own) {
            foreach ($other->coordinates() as $theirs) {
                if ($own->touches($theirs)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function player(): int
    {
        return $this->player;
    }
}
