<?php
namespace App\Model\Match;
use App\Model\Ship\Ship;
use App\Model\Ship\ShipsCollideException;
use App\Model\Vector2;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Match
 *
 * @package App\Model
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Match
{
    const PHASE_WAITING = 1;
    const PHASE_PLAYING = 2;
    const PHASE_FINISHED = 3;

    /**
     * @var MatchID
     */
    private $id;

    /**
     * @var int
     */
    protected $phase = self::PHASE_WAITING;

    /**
     * @var ArrayCollection|Ship[]
     */
    protected $ships;

    /**
     * Match constructor.
     * @param MatchId $id
     */
    public function __construct(MatchId $id)
    {
        $this->id = $id;
        $this->ships = new ArrayCollection();
    }

    /**
     * @return MatchId
     */
    public function id(): MatchId
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function phase(): int
    {
        return $this->phase;
    }

    /**
     * @return iterable
     */
    public function shipSet(): iterable
    {
        // @TODO implement
    }

    public function grids()
    {
        // @TODO implement
    }

    /**
     * Places a ship on the grid
     *
     * @throws ShipsCollideException
     *
     * @param int $player
     * @param int $x
     * @param int $y
     * @param int $length
     * @param int $direction
     *
     * @return Ship
     */
    public function placeShip(int $player, int $x, int $y, int $length, int $direction): Ship
    {
        // @TODO check fit in grid
        /** @var Vector2[] $coordinates */
        $coordinates = [new Vector2($x, $y)];
        for ($i = 1; $i < $length; ++$i) {
            $coordinates[] = $coordinates[$i - 1]->move($direction);
        }

        $ship = new Ship($this->id(), count($this->ships) + 1, $coordinates, $player);
        foreach ($this->ships as $placedShip) {
            if ($placedShip->collidesWith($ship)) {
                throw new ShipsCollideException();
            }
        }

        $this->ships->add($ship);
        return $ship;
    }
}
