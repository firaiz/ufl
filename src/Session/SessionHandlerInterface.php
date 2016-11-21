<?php
namespace UflAs\Session;

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}
if (!defined('IS_LEGACY_PHP')) {
    define('IS_LEGACY_PHP', PHP_VERSION_ID < 50400);
}
if (IS_LEGACY_PHP) {
    interface SessionHandlerInterface
    {
        public function close();
        public function destroy($session_id);
        public function gc($maxlifetime);
        public function open($save_path, $name);
        public function read($session_id);
        public function write($session_id, $session_data);
    }
} else {
    interface SessionHandlerInterface extends \SessionHandlerInterface
    {
        // empty
    }
}