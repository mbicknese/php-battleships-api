<?php
namespace App\Model\Match;

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
     * Match constructor.
     * @param MatchId $id (optional)
     */
    public function __construct(MatchId $id)
    {
        $this->id = $id;
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

}
