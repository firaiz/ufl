<?php

use AnySys\Storage;

if (!function_exists('storage')) {
    function storage($key, $isCreate = false)
    {
        return Storage::getInstance()->getPath($key, $isCreate);
    }
}

