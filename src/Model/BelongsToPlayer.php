<?php
namespace App\Model;

/**
 * Interface BelongsToPlayer
 * @package App\Model
 */
interface BelongsToPlayer
{
    /**
     * @return int
     */
    public function player(): int;
}
