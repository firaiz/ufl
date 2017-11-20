<?php

use UflAs\Storage;
use UflAs\StringUtility;

if (!function_exists('storage')) {
    function storage($key, $isCreate = false)
    {
        return Storage::getInstance()->getPath($key, $isCreate);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token($id = null) {
        $idText = '';
        if (!is_null($id)) {
            $idText = ' id="'.$id.'"';
        }
        return '<input type="hidden"'.$idText.' name="'.\UflAs\Security\Csrf::CSRF_TAG.'" value="'.Security::takeCSRFToken().'">';
    }
}

if (!function_exists('uuid')) {
    function uuid() {
        return StringUtility::randomUUID();
    }
}