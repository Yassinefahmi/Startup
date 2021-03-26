<?php


namespace App\General;


class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();

        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$message) {
            $message['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function setFlashMessage(string $key, string $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function issetFlashMessage(string $key): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }

    public function getFlashMessage(string $key): string
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'];
    }

    public function getFlashMessages()
    {
        return $_SESSION[self::FLASH_KEY];
    }

    public function issetFlashMessages(): bool
    {
        return empty($_SESSION[self::FLASH_KEY]);
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$message) {
            if ($message['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}