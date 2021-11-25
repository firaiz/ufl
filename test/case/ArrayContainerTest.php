<?php

namespace Ufl\TestCase;

use PHPUnit\Framework\TestCase;
use Ufl\TestClass\TestArrayContainer;

class ArrayContainerTest extends TestCase
{
    public function testUsePrefixInit()
    {
        $container = new TestArrayContainer('foo.bar');
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array(
                'foo.bar' => array('a' => 'v')
            )
        ), json_encode($container));
    }

    public function testInit()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testMultipleSet()
    {
        $container = new TestArrayContainer();
        $container->set('a.b.c', 'v');
        self::assertEquals(json_encode(
            array('a' => array('b' => array('c' => 'v')))
        ), json_encode($container));
    }

    public function testMultipleGet()
    {
        $container = new TestArrayContainer();
        $container->set('a.b.c', 'v');
        self::assertEquals('v', $container->get('a.b.c'));
    }

    public function testMultipleDel()
    {
        $container = new TestArrayContainer();
        $container->set('a.b.c', 'v');
        self::assertEquals(json_encode(
            array('a' => array('b' => array('c' => 'v')))
        ), json_encode($container));

        $container->del('a.b');
        self::assertEquals(json_encode(
            array('a' => array())
        ), json_encode($container));
    }

    public function testSet()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testGet()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        self::assertEquals('v', $container->get('a'));
    }

    public function testDel()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));

        $container->del('a');
        self::assertEquals(json_encode(
            array()
        ), json_encode($container));
    }
}
