<?php

namespace Firaiz\Ufl\Security;

use Firaiz\Ufl\Container\SessionContainer;
use Firaiz\Ufl\Exception\Session\NotStarted;
use Firaiz\Ufl\Session;
use Firaiz\Ufl\StringUtility;

/**
 * Class Csrf
 * @package Firaiz\Ufl\Security
 */
class Csrf
{
    final public const CSRF_TAG = '__secure__';
    final public const SECRET_TOKEN = 'SecretToken';
    final public const FIXED_TOKEN = 'SecretFixedToken';

    /**
     * @var ?SessionContainer
     */
    private static ?SessionContainer $container = null;

    /**
     * @throws NotStarted
     */
    private static function container(): SessionContainer
    {
        if (!(self::$container instanceof SessionContainer)) {
            $session = Session::getInstance();
            if ($session->isStarted()) {
                self::$container = new SessionContainer(self::CSRF_TAG);
                self::$container->set(self::FIXED_TOKEN, self::$container->get(self::FIXED_TOKEN, StringUtility::random(32, false)));
            } else {
                throw new NotStarted();
            }
        }
        return self::$container;
    }

    /**
     * @param string|null $token
     * @throws NotStarted
     */
    protected static function generateToken(string $token = null): string
    {
        $securityContainer = self::container();
        if (is_null($token)) {
            $token = $securityContainer->get(self::SECRET_TOKEN, StringUtility::random(64, false));
        }
        $securityContainer->set(self::SECRET_TOKEN, $token);
        $fixedToken = $securityContainer->get(self::FIXED_TOKEN);
        return hash('sha512', $fixedToken . $token);
    }

    /**
     * @throws NotStarted
     */
    public static function takeToken(): string
    {
        if (!is_null(self::container())) {
            return self::generateToken();
        }
        return '';
    }

    /**
     * @throws NotStarted
     */
    public static function isValidToken(string $token): bool
    {
        return static::takeToken() === $token && $token !== '';
    }

    /**
     * @throws NotStarted
     */
    public static function regenerateToken(): string
    {
        return self::generateToken(StringUtility::random(64, false));
    }
}