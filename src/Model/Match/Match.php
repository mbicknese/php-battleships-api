<?php
namespace App\Model\Match;

use App\Common\Collections\PlayerCollection;
use App\Model\Grid;
use App\Model\Ship\Ship;
use App\Model\Ship\ShipAlreadyPlacedException;
use App\Model\Ship\ShipCoordinate;
use App\Model\Ship\ShipPlacement;
use App\Model\Ship\ShipsCollideException;
use App\Model\Shot\Shot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * @var Collection|MatchPhase[]
     */
    protected $phases;

    /**
     * @var PlayerCollection|Ship[][]
     */
    protected $ships;
    /**
     * @var PlayerCollection|Shot[][]
     */
    protected $shots;

    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var array|int[]
     */
    protected $shipSet;
    /**
     * @var int
     */
    protected $slots;
    /**
     * @var Collection|Player[]
     */
    protected $players;

    /**
     * Match constructor.
     * @param MatchId $id
     * @param Grid $grid (optional)
     * @param array $shipSet (optional)
     * @param int $slots (optional)
     */
    public function __construct(MatchId $id, Grid $grid = null, array $shipSet = null, int $slots = 2)
    {
        $this->id = $id;
        $this->ships = new PlayerCollection();
        $this->shots = new PlayerCollection();
        $this->players = new ArrayCollection();
        $this->phases = new ArrayCollection([new MatchPhase($this, self::PHASE_WAITING)]);

        $this->grid = $grid ?: new Grid(15, 15);
        $this->shipSet = $shipSet ?: [5, 4, 3, 3, 2];
        $this->slots = $slots;
    }

    /**
     * @return MatchId
     */
    public function id(): MatchId
    {
        return $this->id;
    }

    /**
     * @return MatchPhase
     */
    public function phase(): MatchPhase
    {
        return array_reduce($this->phases->toArray(), function (?matchPhase $carry, matchPhase $item) {
            if (! $carry) {
                return $item;
            }
            return $carry->startedAt() > $item->startedAt() ? $carry : $item;
        });
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
     * @throws ShipAlreadyPlacedException
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

        /** @var ShipCoordinate[] $coordinates */
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
        $this->progressToPlayingIfApplicable();
        return $ship;
    }

    /**
     * Checks if all players placed their ships and if so, progresses match phase to "playing"
     */
    protected function progressToPlayingIfApplicable(): void
    {
        for ($player = 1; $player <= $this->slots; ++$player) {
            foreach ($this->shipSet() as $shipType) {
                if ($this->isAnotherShipAllowed($player, $shipType)) {
                    return;
                }
            }
        }

        $this->progressToPhase(static::PHASE_PLAYING);
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
     * @param int|null $player
     * @return array
     * @fixme See if Doctrine can be set to auto map to PlayerCollection
     */
    public function ships(int $player = null): array
    {
        if (! $this->ships instanceof PlayerCollection) {
            $this->ships = new PlayerCollection($this->ships->toArray());
        }
        return $this->ships->toArray($player);
    }

    /**
     * @param int|null $player
     * @return array
     */
    public function shots(int $player = null): array
    {
        if (! $this->shots instanceof PlayerCollection) {
            $this->shots = new PlayerCollection($this->shots->toArray());
        }
        return $this->shots->toArray($player);
    }

    /**
     * @return Player
     */
    public function join(): Player
    {
        if (count($this->players) >= $this->slots) {
            throw new NoSlotsAvailableException();
        }

        $player = new Player($this, count($this->players) + 1);
        $this->players->add($player);
        return $player;
    }

    /**
     * @return Player[]|Collection
     */
    public function players(): Collection
    {
        return $this->players;
    }

    /**
     * @param int $phase
     */
    public function progressToPhase(int $phase): void
    {
        $this->phases->add(new MatchPhase($this, $phase));
    }

    /**
     * @param int $player
     * @param int $x
     * @param int $y
     * @return Shot
     *
     * @fixme Think about multiplayer gameplay and who gets fired upon
     */
    public function fireShot(int $player, int $x, int $y): Shot
    {
        $shot = new Shot($x, $y, $this, $player);
        if ($shot->isOffGrid($this->grid())) {
            throw new EntityOffGridException();
        }
        $opponent = $player === 1 ? 2 : 1;

        foreach ($this->ships($opponent) as $ship) {
            if ($shot->doesHit($ship)) {
                $shot->hits($ship, $this->shots($player));
                break;
            }
        }

        return $shot;
    }
}
