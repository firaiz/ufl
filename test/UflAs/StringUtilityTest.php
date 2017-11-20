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
    }

    public function testLengthWithNormal()
    {
        $str = StringUtility::random(32);
        $this->assertEquals(strlen($str), 32);
    }

    public function testLengthWithNormalLoop()
    {
        for ($i = 1; $i <= 512; $i++) {
            $str = StringUtility::random($i);
            $this->assertEquals(strlen($str), $i);
        }
    }

    public function testLengthWithFastLoop()
    {
        for ($i = 1; $i <= 512; $i++) {
            $str = StringUtility::random($i);
            $this->assertEquals(strlen($str), $i);
        }
    }
}
