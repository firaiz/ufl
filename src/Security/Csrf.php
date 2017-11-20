<?php

namespace UflAs\Security;

use Exception\Session\NotStarted;
use UflAs\Session;
use UflAs\SessionContainer;
use UflAs\StringUtility;

class Csrf
{
    const CSRF_TAG = '__secure__';
    const SECRET_TOKEN = 'SecretToken';
    const FIXED_TOKEN = 'SecretFixedToken';

    /**
     * @var SessionContainer
     */
    private static $container;

    /**
     * @return SessionContainer
     * @throws NotStarted
     */
    private static function container()
    {
        if (!(self::$container instanceof SessionContainer)) {
            $session = Session::getInstance();
            if ($session->isStarted()) {
                self::$container = new SessionContainer(self::CSRF_TAG);
                self::$container->set(self::FIXED_TOKEN, self::$container->get(self::FIXED_TOKEN, StringUtility::random()));
            } else {
                throw new NotStarted();
            }
        }
        return self::$container;
    }

    /**
     * @param string $token
     * @return string
     */
    protected static function generateToken($token = null)
    {
        $securityContainer = self::container();
        if (is_null($token)) {
            $token = $securityContainer->get(self::SECRET_TOKEN, StringUtility::random(64));
        }
        $securityContainer->set(self::SECRET_TOKEN, $token);
        $fixedToken = $securityContainer->get(self::FIXED_TOKEN);
        return hash('sha512', $fixedToken . $token);
    }

    /**
     * @return string
     */
    public static function takeToken()
    {
        if (!is_null(self::$container)) {
            return self::generateToken();
        }
        return '';
    }

    /**
     * @param string $token
     * @return bool
     */
    public static function isValidToken($token)
    {
        return static::takeToken() === $token && $token !== '';
    }

    /**
     * @return string
     */
    public static function regenerateToken()
    {
        return self::generateToken(StringUtility::random(64));
    }
}