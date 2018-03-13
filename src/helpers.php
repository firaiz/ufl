<?php

use UflAs\Security\Csrf;
use UflAs\Storage;
use UflAs\StringUtility;

if (!function_exists('storage')) {
    function storage($key, $isCreate = false, $permission = Storage::DEFAULT_PERMISSION)
    {
        return Storage::getInstance()->getPath($key, $isCreate, $permission);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token($id = null) {
        $idText = '';
        if (!is_null($id)) {
            $idText = ' id="'.$id.'"';
        }
        return '<input type="hidden"'.$idText.' name="'. Csrf::CSRF_TAG.'" value="'.Csrf::takeToken().'">';
    }
}

if (!function_exists('uuid')) {
    function uuid() {
        return StringUtility::randomUUID();
    }
}