<?php
namespace App\Model;

/**
 * Class Vector2
 *
 * Used to communicate coordinates on the grid
 *
 * @package App\Model
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Vector2
{
    const DIRECTION_NORTH = 0;
    const DIRECTION_EAST = 1;
    const DIRECTION_SOUTH = 2;
    const DIRECTION_WEST = 3;

    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;

    /**
     * Vector2 constructor.
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function x(): int
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function y(): int
    {
        return $this->y;
    }

    /**
     * @param int $direction
     * @param int $magnitude (optional)
     * @return Vector2
     */
    public function move(int $direction, int $magnitude = 1): Vector2
    {
        $moved = clone $this;
        switch ($direction) {
            case self::DIRECTION_NORTH:
                $moved->y -= $magnitude;
                break;
            case self::DIRECTION_EAST:
                $moved->x += $magnitude;
                break;
            case self::DIRECTION_SOUTH:
                $moved->y += $magnitude;
                break;
            case self::DIRECTION_WEST:
                $moved->x -= $magnitude;
                break;
        }
        return $moved;
    }

    /**
     * @param Vector2 $other
     * @return bool
     */
    public function equals(Vector2 $other): bool
    {
        return $this->x() == $other->x() && $this->y() == $other->y();
    }

    /**
     * Determines whether two vectors are touching, or on top of each other
     *
     * [2,3] touches [2,2]
     * [3,2] touches [2,2]
     * [1,2] touches [2,2]
     * [1,1] does not touch [2,2]
     *
     * @param Vector2 $other
     * @return bool
     */
    public function touches(Vector2 $other): bool
    {
        return abs($this->x() - $other->x()) + abs($this->y() - $other->y()) < 2;
    }

    /**
     * @param Grid $grid
     * @return bool
     */
    public function isOffGrid(Grid $grid): bool
    {
        return (
            $this->x() > $grid->width() ||
            $this->x() < 0 ||
            $this->y() > $grid->height() ||
            $this->y() < 0
        );
    }
}
