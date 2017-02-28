<?php
namespace App\Repository\Type;

use App\Uid64\Doctrine\Uid64Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use App\Model\Match\MatchId;

/**
 * Class MatchIdType
 *
 * @package App\Repository\Type
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 */
class MatchIdType extends Uid64Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): MatchId
    {
        return new MatchId($value);
    }

    public function getName(): string
    {
        return 'match_id';
    }
}
