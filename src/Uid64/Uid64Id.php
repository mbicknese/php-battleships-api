<?php
namespace App\Uid64;

/**
 * Class Uid64Id
 *
 * @package App\Uid64
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Uid64Id
{
    /**
     * @var string
     */
    private $id;

    /**
     * MatchId constructor.
     * @param string $uid64
     */
    public function __construct(string $uid64 = null)
    {
        $this->id = $uid64 ?: Uid64::new();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
