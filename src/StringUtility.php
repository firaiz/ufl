<?php

namespace Ufl;

use Exception;

/**
 * Class StringUtility
 * @package Ufl
 */
class StringUtility
{
    /**
     * @param string $separator
     * @param int $version
     * @return string
     * @throws Exception
     */
    public static function uuid(string $separator = '-', int $version = 4): string
    {
        return match ($version) {
            4 => static::randomUUID($separator),
            default => '',
        };
    }

    /**
     * @param string $separator
     * @return string
     * @throws Exception
     */
    public static function randomUUID(string $separator = '-'): string
    {
        return sprintf(
            implode($separator, array('%04x%04x', '%04x', '%04x', '%04x', '%04x%04x%04x')),
            // 32 bits for "time_low"
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            // 16 bits for "time_mid"
            random_int(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            random_int(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            random_int(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            random_int(0, 0xffff),
            random_int(0, 0xffff),
            random_int(0, 0xffff)
        );
    }

    /**
     * @param int $length
     * @param bool $isFast
     * @return false|string
     * @throws Exception
     */
    public static function random(int $length = 32, bool $isFast = true): bool|string
    {
        if ($isFast) {
            $count = $length / 32;
            $randomStr = '';
            for ($i = 0; $i < $count; $i++) {
                $randomStr .= self::randomUUID('');
            }
        } else {
            $generateLength = ceil($length / 2);
            $str = random_bytes($generateLength);
            if ($str === '') {
                return self::random($length);
            }
            $randomStr = bin2hex($str);
        }
        return substr($randomStr, 0, $length);
    }
}
