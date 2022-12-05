<?php

namespace Firaiz\Ufl\TestCase;

use PHPUnit\Framework\TestCase;
use Firaiz\Ufl\Session;
use Firaiz\Ufl\TestClass\TestSessionContainer;

class SessionContainerTest extends TestCase
{
    public function testUsePrefixInit()
    {
        $container = new TestSessionContainer('foo.bar');
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array(
            'foo.bar' => array('a' => 'v')
            )
        ), json_encode($container));
    }

    public function testInit()
    {
        $container = new TestSessionContainer();
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testMultipleSet()
    {
        $container = new TestSessionContainer();
        $container->set('a.b.c', 'v');
        self::assertEquals(json_encode(
            array('a' => array('b' => array('c' => 'v')))
        ), json_encode($container));
    }

    public function testMultipleGet()
    {
        $container = new TestSessionContainer();
        $container->set('a.b.c', 'v');
        self::assertEquals('v', $container->get('a.b.c'));
    }

    public function testMultipleDel()
    {
        $container = new TestSessionContainer();
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
        $container = new TestSessionContainer();
        $container->set('a', 'v');
        self::assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testGet()
    {
        $container = new TestSessionContainer();
        $container->set('a', 'v');
        self::assertEquals('v', $container->get('a'));
    }

    public function testDel()
    {
        $container = new TestSessionContainer();
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
