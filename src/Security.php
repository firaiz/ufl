<?php

namespace UflAs;

use UflAs\Session;
use UflAs\SessionContainer;

class Security
{
    const CSRF_TAG = '__secure__';

    public static function takeCSRFToken()
    {
        $session = Session::getInstance();
        if ($session->isStarted()) {
            $securityContainer = new SessionContainer(self::CSRF_TAG);
            $tokenName = 'secureToken';
            $token = $securityContainer->get($tokenName, StringUtility::uuid(''));
            $securityContainer->set($tokenName, $token);
            $sid = $session->getSID();
            return hash('sha512', $sid.$token);
        }
        return '';
    }

    public static function isValidCSRFToken($token)
    {
        return static::takeCSRFToken() === $token && $token !== '';
    }
}