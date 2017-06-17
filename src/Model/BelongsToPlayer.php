<?php
namespace App\Model;

/**
 * Interface BelongsToPlayer
 * @package App\Model
 */
interface BelongsToPlayer
{
    /**
     * @return int|null
     */
    public function player(): ?int;
}
