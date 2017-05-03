<?php
namespace App\Model;

/**
 * Class Vector2
 *
 * Used to communicate and calculate coordinates on the grid.
 * Keep in mind that the vectors are calculated with an origin in the top
 * left corner. Descriptive names are based on this premise. i.e. To move
 * upwards one needs to decrease the Y value, moving to the right still
 * requires to increase the X value.
 *
 * @fixme The grid class can be dropped in favor of using a vector2 to describe the grid.
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
     * Rotated by -90 degrees because of the X Y notation.
     */
    const COMPASS = [
        -1 => [-1 => -1, 0 => self::DIRECTION_WEST, 1 => -1],
        0  => [-1 => self::DIRECTION_NORTH, 0 => -1, 1 => self::DIRECTION_SOUTH],
        1  => [-1 => -1, 0 => self::DIRECTION_EAST, 1 => -1],
    ];

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
     * Creates a new vector with a moved target
     *
     * @param int $direction
     * @param int $magnitude (optional)
     *
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

    /**
     * Calculates the difference between two vectors
     *
     * @param Vector2 $origin
     * @param Vector2 $target
     *
     * @return Vector2
     */
    public static function diff(Vector2 $origin, Vector2 $target): self
    {
        return new static(
            $target->x() - $origin->x(),
            $target->y() - $origin->y()
        );
    }

    /**
     * Tries to determine the direction for a single axis
     *
     * @return int
     * @throws CannotDetermineException on magnitude on multiple axis
     */
    public function singleAxisDirection(): int
    {
        if ($this->x() !== 0 && $this->y() !== 0) {
            throw CannotDetermineException::magnitudeOnMultipleAxis($this);
        }

        return self::COMPASS[$this->normalize()->x()][$this->normalize()->y()];
    }

    /**
     * Returns a vector where the maximum magnitude in a single direction equals one
     *
     * NOTE: the naming might be wrong here. As normalize might indicate that
     * the actual magnitude of the vector should equal one. Will have to look
     * into this. For now, this logic is the desired behaviour.
     *
     * @return Vector2
     */
    public function normalize(): self
    {
        $normalized = clone $this;
        $strongestDirection = max(abs($this->x()), abs($this->y()));
        $normalized->x = $normalized->x() ? $normalized->x() / $strongestDirection : 0;
        $normalized->y = $normalized->y() ? $normalized->y() / $strongestDirection : 0;
        return $normalized;
    }

    /**
     * @return float
     */
    public function magnitude(): float
    {
        return sqrt(pow(abs($this->x()), 2) + pow(abs($this->y()), 2));
    }
}
