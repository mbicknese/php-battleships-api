<?php
namespace App\Repository\Type;

use App\Model\Shot\ShotId;
use App\Uid64\Doctrine\Uid64Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class ShotIdType
 *
 * @package App\Repository\Type
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShotIdType extends Uid64Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ShotId
    {
        return new ShotId($value);
    }

    public function getName(): string
    {
        return 'shot_id';
    }
}
