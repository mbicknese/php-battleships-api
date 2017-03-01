<?php
namespace App\Model\Ship;

use App\Model\Match\Match;
use Doctrine\Common\Collections\Collection;

/**
 * Class Ship
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Ship
{
    /**
     * @var ShipId
     */
    private $id;
    /**
     * @var Match
     */
    protected $match;
    /**
     * @var int
     */
    private $sequence;
    /**
     * @var iterable|Collection|ShipCoordinate[]
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
     * @param iterable|ShipCoordinate[]|Collection $coordinates
     * @param int $player (optional)
     * @param ShipId $id
     */
    public function __construct(Match $match, int $sequence, iterable $coordinates, int $player = 0, ShipId $id = null)
    {
        $this->id = $id ?: new ShipId();
        $this->match = $match;
        $this->sequence = $sequence;
        $this->coordinates = $coordinates;
        $this->player = $player;

        foreach ($this->coordinates() as $coordinate) {
            $coordinate->attachToShip($this);
        }
    }

    /**
     * @return iterable|ShipCoordinate[]|Collection
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
