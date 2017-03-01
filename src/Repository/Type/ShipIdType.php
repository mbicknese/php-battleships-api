<?php
namespace App\Repository\Type;

use App\Model\Ship\ShipId;
use App\Uid64\Doctrine\Uid64Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Class ShipIdType
 *
 * @package App\Repository\Type
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class ShipIdType extends Uid64Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ShipId
    {
        return new ShipId($value);
    }

    public function getName(): string
    {
        return 'ship_id';
    }
}
