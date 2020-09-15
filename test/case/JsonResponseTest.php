<?php

namespace UflAs\TestCase;

use PHPUnit\Framework\TestCase;
use UflAs\JsonResponse;

class JsonResponseTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testOne()
    {
        $jsonResponse = JsonResponse::getInstance();
        $jsonResponse->assign('a', 'b');
        ob_start();
        $jsonResponse->write();
        $result = ob_get_clean();
        $this->assertEquals($result, '{"a":"b"}');
    }

    /**
     * @runInSeparateProcess
     */
    public function testObject()
    {
        $jsonResponse = JsonResponse::getInstance();

        $obj = new \stdClass();
        $obj->a = "1";
        $obj->b = 2;
        $obj->c = array(1,2,3,4,5);

        $jsonResponse->assign('a', $obj);
        ob_start();
        $jsonResponse->write();
        $result = ob_get_clean();
        $this->assertEquals($result, '{"a":{"a":"1","b":2,"c":[1,2,3,4,5]}}');
    }
}
