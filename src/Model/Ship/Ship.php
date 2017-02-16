<?php
namespace App\Model\Ship;

/**
 * Class Ship
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Ship
{
    /**
     * @var int
     */
    protected $hitPoints;

    /**
     * @var int
     */
    protected $damage;

    /**
     * Ship constructor.
     * @param int $hitPoints
     */
    public function __construct(int $hitPoints)
    {
        $this->hitPoints = $hitPoints;
    }

    /**
     * @return int
     */
    public function hitPoints(): int
    {
        return $this->hitPoints - $this->damage;
    }

    /**
     * @throws ShipAlreadySunkException
     */
    public function hit(): void
    {
        if ($this->hasSunk()) {
            throw new ShipAlreadySunkException();
        }

        $this->damage++;
    }

    /**
     * @return bool
     */
    public function hasSunk(): bool
    {
        return $this->damage == $this->hitPoints;
    }
}
