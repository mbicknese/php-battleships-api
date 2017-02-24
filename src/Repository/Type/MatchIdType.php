<?php
namespace App\Repository\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;
use App\Model\Match\MatchId;

/**
 * Class MatchIdType
 *
 * @package App\Repository\Type
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchIdType extends BigIntType
{
    const MYTYPE = 'MatchIdType';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new MatchId($value);
    }

    public function getName()
    {
        return self::MYTYPE;
    }
}
