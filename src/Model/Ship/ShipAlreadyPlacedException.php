<?php
namespace App\Model\Ship;

use RuntimeException;

/**
 * Class ShipAlreadyPlacedException
 *
 * @package App\Model\Ship
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipAlreadyPlacedException extends RuntimeException
{
    protected $message = 'The limit for this kind of ships has been reached';
}
