<?php

namespace UflAs\TestCase;

include_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use UflAs\TestClass\TestArrayContainer;

class ArrayContainerTest extends TestCase
{
    public function testUsePrefixInit()
    {
        $container = new TestArrayContainer('foo.bar');
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array(
            'foo.bar' => array('a' => 'v')
            )
        ), json_encode($container));
    }

    public function testInit()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testMultipleSet()
    {
        $container = new TestArrayContainer();
        $container->set('a.b.c', 'v');
        $this->assertEquals(json_encode(
            array('a' => array('b' => array('c' => 'v')))
        ), json_encode($container));
    }

    public function testMultipleGet()
    {
        $container = new TestArrayContainer();
        $container->set('a.b.c', 'v');
        $this->assertEquals('v', $container->get('a.b.c'));
    }

    public function testMultipleDel()
    {
        $container = new TestArrayContainer();
        $container->set('a.b.c', 'v');
        $this->assertEquals(json_encode(
            array('a' => array('b' => array('c' => 'v')))
        ), json_encode($container));

        $container->del('a.b');
        $this->assertEquals(json_encode(
            array('a' => array())
        ), json_encode($container));
    }

    public function testSet()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testGet()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        $this->assertEquals('v', $container->get('a'));
    }

    public function testDel()
    {
        $container = new TestArrayContainer();
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));

        $container->del('a');
        $this->assertEquals(json_encode(
            array()
        ), json_encode($container));
    }
}
