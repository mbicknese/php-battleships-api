<?php
namespace App\Model\Ship;

use RuntimeException;

/**
 * Class ShipAlreadySunkException
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipAlreadySunkException extends RuntimeException
{
    protected $message = 'This ship won\'t get any deader mate';
}
