<?php

namespace Firaiz\Ufl\TestCase;

use PHPUnit\Framework\TestCase;
use stdClass;
use Firaiz\Ufl\JsonResponse;

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
        self::assertEquals('{"a":"b"}', $result);
    }

    /**
     * @runInSeparateProcess
     */
    public function testObject()
    {
        $jsonResponse = JsonResponse::getInstance();

        $obj = new stdClass();
        $obj->a = "1";
        $obj->b = 2;
        $obj->c = [1, 2, 3, 4, 5];

        $jsonResponse->assign('a', $obj);
        ob_start();
        $jsonResponse->write();
        $result = ob_get_clean();
        self::assertEquals('{"a":{"a":"1","b":2,"c":[1,2,3,4,5]}}', $result);
    }
}
