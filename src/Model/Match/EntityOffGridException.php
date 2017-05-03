<?php
namespace App\Model\Match;

use RuntimeException;

/**
 * Class EntityOffGridException
 *
 * @package App\Model\Match
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class EntityOffGridException extends RuntimeException
{
    protected $message = 'Whatever you were doing, please keep it on the grid where we can see it';
}
