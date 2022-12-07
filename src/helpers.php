<?php

use Firaiz\Ufl\Security\Csrf;
use Firaiz\Ufl\Storage;
use Firaiz\Ufl\StringUtility;

if (!function_exists('storage')) {
    function storage($key, $isCreate = false, $permission = Storage::DEFAULT_PERMISSION): string
    {
        return Storage::getInstance()->getPath($key, $isCreate, $permission);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token($id = null): string
    {
        $idText = '';
        if (!is_null($id)) {
            $idText = ' id="' . $id . '"';
        }
        /** @noinspection PhpUnhandledExceptionInspection */
        return '<input type="hidden"' . $idText . ' name="' . Csrf::CSRF_TAG . '" value="' . Csrf::takeToken() . '">';
    }
}

if (!function_exists('uuid')) {
    function uuid(): string
    {
        return StringUtility::randomUUID();
    }
}

if (!function_exists('http_response_code')) {
    function http_response_code($code = null) {
        if ($code !== NULL) {
            $text = match ($code) {
                100 => 'Continue',
                101 => 'Switching Protocols',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Moved Temporarily',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Time-out',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Large',
                415 => 'Unsupported Media Type',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Time-out',
                505 => 'HTTP Version not supported',
                default => 'custom status',
            };
            $protocol = ($_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
        }
        return $code;
    }
}
