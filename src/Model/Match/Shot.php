<?php
namespace App\Model\Match;

/**
 * Class Shot
 *
 * @package App\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Shot
{
    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * Shot constructor.
     * @param int $x
     * @param int $y
     */
    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
