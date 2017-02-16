<?php
namespace App\Uid64;

/**
 * Class Uid64
 *
 * @package App\Uid64
 * @author  Maarten Bicknese <maarten.bicknese@devmob.com>
 *
 * The idea is to have UIDs of 64 bits. These ids can be stored as bigint in
 * databases. As well as that they can be converted to characters in order to
 * create a URL friendly id.
 *
 * Speculation: integers provide for faster indexing in database compared to
 * strings.
 *
 * Using the first bits for a timestamp helps:
 *  - Ensuring unique ids.
 *  - Making sort by id the same as sort by creation date (though you should
 *    not rely on that.)
 *  - Making it possible to retrieve creation date when lost.
 */
final class Uid64
{
    /**
     * Offsetting the unix time gives us more time to create unique ids in.
     * Since we won't create ids in the past, this should not matter.
     */
    const UNIX_TIME_OFFSET = 1487183454;

    /**
     * Builds a new Uid64
     *
     * Returns a string instead of an integer, to quote the Doctrine manual:
     *   For compatibility reasons this type is not converted to an integer as
     *   PHP can only represent big integer values as real integers on systems
     *   with a 64-bit architecture and would fall back to approximated float
     *   values otherwise which could lead to false assumptions in applications.
     *
     * @return string
     * @throws Uid64ExpiredException
     */
    public static function new(): string
    {
        self::checkPreconditions();

        $time = self::currentTimeInMilliseconds();
        $binStrTime = decbin($time);
        if (strlen($binStrTime) > 40) {
            throw new Uid64ExpiredException();
        }

        $random = rand(0, 8388607);
        $binStrRandom = str_pad(decbin($random), 23, '0', STR_PAD_LEFT);

        return base_convert($binStrTime . $binStrRandom, 2, 10);
    }

    /**
     * Transforms the UID64 in a shorter text representation
     *
     * @param string $uid64
     * @return string
     */
    public static function toText(string $uid64): string
    {
        self::checkPreconditions();
        if (!self::isUid64($uid64)) {
            throw new InvalidUid64Exception();
        }

        return base_convert($uid64, 10, 36);
    }

    /**
     * @return int
     */
    protected static function currentTimeInMilliseconds(): int
    {
        $microTime = microtime(true) - self::UNIX_TIME_OFFSET;
        return round($microTime * 1000);
    }

    /**
     * @param string $test
     * @return bool
     */
    public static function isUid64(string $test): bool
    {
        self::checkPreconditions();
        return (
            preg_match('/^[0-9]/', $test) &&
            strlen(base_convert($test, 10, 2)) <= 63
        );
    }

    /**
     * @todo add support for 32-bit systems with GMP plugin
     * @throws Uid64NotSupportedException
     */
    private static function checkPreconditions(): void
    {
        if (PHP_INT_SIZE !== 8) {
            throw new Uid64NotSupportedException();
        }
    }
}
