<?php

class USession
{
    public function __construct() {
        session_start();
    }

    public function getValue(string $key): ?string {

        if (isset($_SESSION[$key])) {
            return $result = $_SESSION[$key];
        } else {
            return null;
        }

    }

    public function setValue(string $key, string $value): void {

        if (gettype($value) == 'object') {
            $value = serialize($value);
        }
        $_SESSION[$key] = $value;

    }

    public function removeValue(string $key): void {

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

    }

    public function deleteSession(): void {

        setcookie('PHPSESSID', '');
        session_unset();
        session_destroy();

    }
}