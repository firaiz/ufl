<?php
if (PHP_VERSION_ID < 50400) {
    if (!interface_exists('SessionHandlerInterface')) {
        /**
         * Interface SessionHandlerInterface
         * @package UflAs\Session
         * @covers
         */
        interface SessionHandlerInterface
        {
            public function close();

            public function destroy($session_id);

            public function gc($maxlifetime);

            public function open($save_path, $name);

            public function read($session_id);

            public function write($session_id, $session_data);
        }
    }

    if (!interface_exists('JsonSerializable')) {
        /**
         * Interface JsonSerializable
         * @package Uflas
         * @covers
         */
        interface JsonSerializable
        {
            public function jsonSerialize();
        }
    }
}
