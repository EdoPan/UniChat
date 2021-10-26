<?php

class USession
{
    public function __construct() {
        session_start();
    }

    public function getValue(string $key) {

        if (isset($_SESSION[$key])) {
            return $result = unserialize(($_SESSION[$key]));
        } else {
            return null;
        }

    }

    public function setValue(string $key, $value) {

        if (gettype($value) == 'object') {
            $value = serialize($value);
        }
        $_SESSION[$key] = $value;

    }

    public function removeValue(string $key) {

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

    }

    public function deleteSession() {

        session_unset();
        setcookie('PHPSESSID', '');
        session_destroy();

    }
}