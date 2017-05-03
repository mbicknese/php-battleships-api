<?php
namespace App\Model\Ship;

use RuntimeException;

/**
 * Class ShipsCollideException
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipsCollideException extends RuntimeException
{
    protected $message = 'There\'s a ship already in this position';
}
