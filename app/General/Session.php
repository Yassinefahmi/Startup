<?php


namespace App\General;


class Session
{
    protected const FLASH_KEY = 'flash_messages';

    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();

        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashMessages as $key => &$message) {
            $message['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    /**
     * Set a session key and value.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get value of given session key.
     *
     * @param $key
     * @return mixed
     */
    public function get($key): mixed
    {
        return $_SESSION[$key] ?? false;
    }

    /**
     * Remove the given session key.
     *
     * @param $key
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Set a flash message.
     *
     * @param string $key
     * @param string $message
     */
    public function setFlashMessage(string $key, string $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    /**
     * Check whether the given flash message key exist.
     *
     * @param string $key
     * @return bool
     */
    public function issetFlashMessage(string $key): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }

    /**
     * Get value of the given flash message key.
     *
     * @param string $key
     * @return string
     */
    public function getFlashMessage(string $key): string
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'];
    }

    /**
     * Get all available flash messages.
     *
     * @return mixed
     */
    public function getFlashMessages(): mixed
    {
        return $_SESSION[self::FLASH_KEY];
    }

    /**
     * Check if there are any flash messages.
     *
     * @return bool
     */
    public function issetFlashMessages(): bool
    {
        return empty($_SESSION[self::FLASH_KEY]);
    }

    /**
     * Session destructor
     */
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