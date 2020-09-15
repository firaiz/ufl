<?php

namespace UflAs\TestCase;

use PHPUnit\Framework\TestCase;
use UflAs\TestClass\TestSessionContainer;

class SessionContainerTest extends TestCase
{
    public function testUsePrefixInit()
    {
        $container = new TestSessionContainer('foo.bar');
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array(
            'foo.bar' => array('a' => 'v')
            )
        ), json_encode($container));
    }

    public function testInit()
    {
        $container = new TestSessionContainer();
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testMultipleSet()
    {
        $container = new TestSessionContainer();
        $container->set('a.b.c', 'v');
        $this->assertEquals(json_encode(
            array('a' => array('b' => array('c' => 'v')))
        ), json_encode($container));
    }

    public function testMultipleGet()
    {
        $container = new TestSessionContainer();
        $container->set('a.b.c', 'v');
        $this->assertEquals('v', $container->get('a.b.c'));
    }

    public function testMultipleDel()
    {
        $container = new TestSessionContainer();
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
        $container = new TestSessionContainer();
        $container->set('a', 'v');
        $this->assertEquals(json_encode(
            array('a' => 'v')
        ), json_encode($container));
    }

    public function testGet()
    {
        $container = new TestSessionContainer();
        $container->set('a', 'v');
        $this->assertEquals('v', $container->get('a'));
    }

    public function testDel()
    {
        $container = new TestSessionContainer();
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
