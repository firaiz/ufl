<?php

namespace Firaiz\Ufl\TestCase;

use Firaiz\Ufl\ArrayUtil;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class ArrayUtilTest extends TestCase
{

    public function testKeyValue()
    {
        self::assertEquals('bar', ArrayUtil::keyValue('foo.bar.val', 1));
        self::assertEquals('foo', ArrayUtil::keyValue('foo.bar.val', 0));
        self::assertEquals('foo', ArrayUtil::keyValue('foo', 0));
        self::assertNull(ArrayUtil::keyValue('foo', 2));
    }

    public function testHead()
    {
        self::assertEquals('a', ArrayUtil::head(['a', 'b', 'c']));
        self::assertFalse(ArrayUtil::head([]));
    }

    public function testAdd()
    {
        $a = [
            'a' => [
                'b' => [
                    'a'
                ]
            ]
        ];
        $ary = [];
        self::assertEquals(json_encode($a), json_encode(ArrayUtil::add($ary, 'a.b', 'a'), JSON_THROW_ON_ERROR));
    }

    public function testValue()
    {
        self::assertEquals('1', ArrayUtil::value(static fn() => '1'));
        self::assertNull(ArrayUtil::value(null));
        self::assertFalse(ArrayUtil::value(false));
        self::assertIsInt(ArrayUtil::value(0));
        self::assertIsFloat(ArrayUtil::value(0.0));
        self::assertIsCallable(static fn() => static function () {

        });
    }

    public function testGet()
    {
        $array = [];
        ArrayUtil::set($array, ArrayUtil::toKey(['a','b','c','d','e']), 'aaa');
        self::assertEquals(json_encode(['d' => ['e' => 'aaa']]),json_encode(ArrayUtil::get($array, 'a.b.c'), JSON_THROW_ON_ERROR));
    }

    public function testToKeys()
    {
        self::assertEquals(['a','b','c','d','e'], ArrayUtil::toKeys('a.b.c.d.e'));
    }

    public function testHas()
    {

        $array = [];
        ArrayUtil::set($array, ArrayUtil::toKey(['a','b','c','d','e']), 'aaa');
        self::assertTrue(ArrayUtil::has($array, ArrayUtil::toKey(['a','b'])));
    }

    public function testToKey()
    {
        self::assertEquals('a.b.c.d.e', ArrayUtil::toKey(['a','b','c','d','e']));
    }

    public function testSet()
    {
        $array = [];
        ArrayUtil::set($array, ArrayUtil::toKey(['a','b','c','d','e']), 'aaa');
        self::assertEquals(json_encode(['a' => ['b' => ['c' => ['d' => ['e' => 'aaa']]]]]),json_encode($array, JSON_THROW_ON_ERROR));
    }

    public function testCount()
    {
        $array = [];
        $key = ArrayUtil::toKey(['a','b']);
        ArrayUtil::add($array, $key, 'aaa');
        ArrayUtil::add($array, $key, 'aaa');
        ArrayUtil::add($array, $key, 'aaa');
        self::assertEquals(3, ArrayUtil::count($array, $key));
    }
}
