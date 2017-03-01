<?php
namespace App\Uid64\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;
use LogicException;

/**
 * Class Uid64Type
 *
 * @package App\Uid64\Doctrine
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
abstract class Uid64Type extends BigIntType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        throw new LogicException('getName method of ' . static::class . ' should be overridden.');
    }
}
