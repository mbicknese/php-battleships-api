<?php
namespace App\Model;

use RuntimeException;

/**
 * Class CannotDetermineException
 *
 * @package App\Model
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class CannotDetermineException extends RuntimeException
{
    /**
     * @param Vector2 $vector2
     * @return CannotDetermineException
     */
    public static function magnitudeOnMultipleAxis(Vector2 $vector2): self
    {
        return new static(sprintf(
            'Vector has magnitude on both X(%s) and Y(%s) axis',
            $vector2->x(),
            $vector2->y()
        ));
    }
}
