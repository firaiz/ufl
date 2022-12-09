<?php
namespace Firaiz\Ufl\TestCase;

use Firaiz\Ufl\Config;
use Firaiz\Ufl\TestClass\TestBaseClass;
use PHPUnit\Framework\TestCase;

class TestBaseClassTest extends TestCase
{

    public function testExecute()
    {
        $obj = new TestBaseClass();
        self::assertEquals(Config::getInstance(), $obj->getConfig());
    }
}
