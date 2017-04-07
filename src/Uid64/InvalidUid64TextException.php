<?php
namespace App\Uid64;

use RuntimeException;

/**
 * Class InvalidUid64TextException
 *
 * @package App\Uid64
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class InvalidUid64TextException extends RuntimeException
{
    /**
     * @param $text
     * @return self
     */
    public static function fromInvalidText($text): self
    {
        return new static(sprintf('"%s" does not match pattern ^[a-z0-9]+$', $text));
    }
}
