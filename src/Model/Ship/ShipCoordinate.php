<?php
namespace App\Model\Ship;

use App\Model\Vector2;

/**
 * Class ShipCoordinate
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipCoordinate extends Vector2
{
    /**
     * @var Ship
     */
    protected $ship;

    /**
     * ShipCoordinate constructor.
     * @param int $x
     * @param int $y
     * @param Ship $ship
     */
    public function __construct($x, $y, Ship $ship = null)
    {
        parent::__construct($x, $y);
        $this->ship = $ship;
    }

    /**
     * @param Ship $ship
     */
    public function attachToShip(Ship $ship): void
    {
        if ($this->ship) {
            throw new CoordinateAlreadyAttachedException();
        }
        $this->ship = $ship;
    }
}
