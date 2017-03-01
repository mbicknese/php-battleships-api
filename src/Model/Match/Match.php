<?php
namespace App\Model\Match;

use App\Model\Grid;
use App\Model\Ship\Ship;
use App\Model\Ship\ShipAlreadyPlacedException;
use App\Model\Ship\ShipCoordinate;
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
     * @var ArrayCollection|Ship[][]
     */
    protected $ships;

    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var array|int[]
     */
    protected $shipSet;

    /**
     * Match constructor.
     * @param MatchId $id
     * @param Grid $grid (optional)
     * @param array $shipSet (optional)
     */
    public function __construct(MatchId $id, Grid $grid = null, array $shipSet = null)
    {
        $this->id = $id;
        $this->ships = new ArrayCollection();
        $this->grid = $grid ?: new Grid(15, 15);
        $this->shipSet = $shipSet ?: [5, 4, 3, 3, 2];
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
     * @return Grid
     */
    public function grid(): Grid
    {
        return $this->grid;
    }

    /**
     * @return array|int[]
     */
    public function shipSet(): array
    {
        return $this->shipSet;
    }

    /**
     * Places a ship on the grid
     *
     * @throws EntityOffGridException
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
        if (! $this->isAnotherShipAllowed($player, $length)) {
            throw new ShipAlreadyPlacedException();
        }

        /** @var Vector2[] $coordinates */
        $coordinates = [new ShipCoordinate($x, $y)];
        for ($i = 1; $i < $length; ++$i) {
            $coordinate = $coordinates[$i - 1]->move($direction);
            if ($coordinate->isOffGrid($this->grid())) {
                throw new EntityOffGridException();
            }
            $coordinates[] = $coordinate;
        }

        $ship = new Ship($this, count($this->ships()) + 1, $coordinates, $player);
        foreach ($this->ships($player) as $placedShip) {
            if ($ship->collidesWith($placedShip)) {
                throw new ShipsCollideException();
            }
        }

        $this->addShip($ship);
        return $ship;
    }

    /**
     * Determines if a player can place another ship of given length
     * @param int $player
     * @param int $length
     * @return bool
     */
    protected function isAnotherShipAllowed(int $player, int $length): bool
    {
        $amountAllowed = array_count_values($this->shipSet())[$length] ?? 0;
        $amountPlaced = 0;
        /** @var Ship $ship */
        foreach ($this->ships($player) as $ship) {
            if ($ship->hitPoints() == $length) {
                $amountPlaced++;
            }
        }
        return $amountPlaced < $amountAllowed;
    }

    /**
     * @param Ship $ship
     */
    protected function addShip(Ship $ship): void
    {
        $this->ships->add($ship);
    }

    /**
     * Returns read only array of ships
     *
     * @param int $player
     * @return array
     */
    public function ships(int $player = null): array
    {
        if ($player === null) {
            return $this->ships->toArray();
        }
        return array_filter($this->ships->toArray(), function (Ship $ship) use ($player) {
            return $ship->player() === $player;
        });
    }
}
