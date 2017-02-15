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
 *
 * The max value for a 64 bit int is: 18446744073709551615
 * Which is 20 characters.
 *
 * A year contains: 31536000000 milliseconds
 * If we were to use 41 bits for storing the timestamp, we can create ids for
 * roughly the next 70 years.
 *
 * 41 bits can store the number 2199023255551.
 * 2199023255551 / 31536000000 = 69,730570000983004
 *
 * Leaving us with 23 bits for randomness. Giving us a 1 / 8388607 per
 * milliseconds chance for collision. A chance, I'm willing to take.
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
     */
    public static function new(): string
    {
        $time = self::currentTimeInMilliseconds();
        $binStrTime = str_pad(decbin($time), 41, '0', STR_PAD_LEFT);
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
        return base_convert($uid64, 2, 36);
    }

    /**
     * @return int
     */
    protected static function currentTimeInMilliseconds(): int
    {
        $microTime = microtime(true) - self::UNIX_TIME_OFFSET;
        return round($microTime * 1000);
    }
}
