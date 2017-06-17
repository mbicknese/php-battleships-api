<?php
namespace App\Common\Collections;

use App\Model\BelongsToPlayer;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PlayerCollection
 *
 * @package App\Common\Collections
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class PlayerCollection extends ArrayCollection
{
    /**
     * @param int|null $player
     * @return array
     */
    public function toArray(int $player = null): array
    {
        if ($player === null) {
            return parent::toArray();
        }
        return array_filter(parent::toArray(), function (BelongsToPlayer $entity) use ($player) {
            return $entity->player() === $player;
        });
    }
}
