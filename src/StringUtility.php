<?php

namespace UflAs;

class StringUtility {
    public static function uuid($separator = '-', $version = 4) {
        switch ($version) {
            case 4:
                return static::randomUUID($separator);
        }
        return '';
    }

    public static function randomUUID($separator = '-') {
        return sprintf(
            implode($separator, array('%04x%04x','%04x','%04x','%04x','%04x%04x%04x')),
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
