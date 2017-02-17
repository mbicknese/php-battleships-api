<?php
namespace App\Model\Ship;

use App\Model\Match\MatchId;
use App\Model\Vector2;
use phpDocumentor\Reflection\Types\Callable_;

/**
 * Class Ship
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class Ship
{
    /**
     * @var MatchId
     */
    private $matchId;
    /**
     * @var int
     */
    private $sequence;
    /**
     * @var iterable
     */
    private $coordinates;
    /**
     * @var int
     */
    private $player;

    /**
     * Ship constructor.
     * @param MatchId $matchId
     * @param int $sequence
     * @param iterable|Vector2[] $coordinates
     * @param int $player (optional)
     */
    public function __construct(MatchId $matchId, int $sequence, iterable $coordinates, int $player = 0)
    {
        $this->matchId = $matchId;
        $this->sequence = $sequence;
        $this->coordinates = $coordinates;
        $this->player = $player;
    }

    /**
     * @return iterable|Vector2[]
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
}
