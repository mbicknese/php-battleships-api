<?php
namespace App\Model\Shot;

use App\Model\BelongsToPlayer;
use App\Model\Match\Match;
use App\Model\Ship\Ship;
use App\Model\Vector2;

/**
 * Class Shot
 *
 * @package App\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Shot extends Vector2 implements BelongsToPlayer
{
    /**
     * @var bool
     */
    private $hasHit;
    /**
     * @var bool
     */
    private $hasSunk;
    /**
     * @var Match
     */
    private $match;
    /**
     * @var int
     */
    private $player;

    /**
     * Shot constructor.
     * @param int $x
     * @param int $y
     * @param Match $match
     * @param int $player
     * @param bool $hasHit
     * @param bool $hasSunk
     */
    public function __construct(int $x, int $y, Match $match, int $player, bool $hasHit = false, bool $hasSunk = false)
    {
        parent::__construct($x, $y);
        $this->match = $match;
        $this->player = $player;
        $this->hasHit = $hasHit;
        $this->hasSunk = $hasSunk;
    }

    /**
     * @return int|null
     */
    public function player(): int
    {
        return $this->player;
    }

    /**
     * @return bool
     */
    public function hasHit(): bool
    {
        return (bool) $this->hasHit;
    }

    /**
     * @return bool
     */
    public function hasSunk(): bool
    {
        return (bool) $this->hasSunk;
    }

    /**
     * @param Ship $ship
     * @param Shot[]|array|null $previousShots
     */
    public function hits(Ship $ship, ?array $previousShots = []): void
    {
        $this->hasHit = true;
        $hitCount = 1;
        foreach ($previousShots as $shot) {
            if ($shot->doesHit($ship)) {
                $hitCount++;
            }
        }
        if ($hitCount == $ship->hitPoints()) {
            $this->hasSunk = true;
        }
    }

    /**
     * @param Ship $ship
     * @return bool
     */
    public function doesHit(Ship $ship): bool
    {
        foreach ($ship->coordinates() as $coordinate) {
            if ($this->x() == $coordinate->x() && $this->y() == $coordinate->y()) {
                return true;
            }
        }
        return false;
    }
}
