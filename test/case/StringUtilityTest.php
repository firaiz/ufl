<?php
namespace Firaiz\Ufl\TestCase;

use PHPUnit\Framework\TestCase;
use Firaiz\Ufl\StringUtility;

class StringUtilityTest extends TestCase
{
    public function testLengthWithFast()
    {
        $str = StringUtility::random(32, true);
        self::assertEquals(32, strlen($str));

        $str = StringUtility::random(32);
        self::assertEquals(32, strlen($str));
    }

    public function testLengthWithLoop()
    {
        for ($i = 1; $i <= 512; $i++) {
            $str = StringUtility::random($i, true);
            self::assertEquals(strlen($str), $i);
        }

        for ($i = 1; $i <= 512; $i++) {
            $str = StringUtility::random($i);
            self::assertEquals(strlen($str), $i);
        }
    }

    public function testMinimumLength()
    {
        $str = StringUtility::random(16, true);
        self::assertEquals(16, strlen($str));

        $str = StringUtility::random(16);
        self::assertEquals(16, strlen($str));
    }
}
