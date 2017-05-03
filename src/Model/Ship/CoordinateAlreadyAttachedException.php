<?php
namespace App\Model\Ship;

use RuntimeException;

/**
 * Class CoordinateAlreadyAttachedException
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class CoordinateAlreadyAttachedException extends RuntimeException
{
    protected $message = 'My knowledge of quantum physics does not cover how a ship can occupy the same space twice';
}
