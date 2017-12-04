<?php
namespace UflAs;

include_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class StringUtilityTest extends TestCase
{
    public function testLengthWithFast()
    {
        $str = StringUtility::random(32, true);
        $this->assertEquals(strlen($str), 32);

        $str = StringUtility::random(32);
        $this->assertEquals(strlen($str), 32);
    }

    public function testLengthWithLoop()
    {
        for ($i = 1; $i <= 512; $i++) {
            $str = StringUtility::random($i, true);
            $this->assertEquals(strlen($str), $i);
        }

        for ($i = 1; $i <= 512; $i++) {
            $str = StringUtility::random($i);
            $this->assertEquals(strlen($str), $i);
        }
    }

    public function testMinimumLength()
    {
        $str = StringUtility::random(16, true);
        $this->assertEquals(strlen($str), 16);

        $str = StringUtility::random(16);
        $this->assertEquals(strlen($str), 16);
    }
}
