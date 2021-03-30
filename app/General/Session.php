<?php


namespace App\General;


use Exception;

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

        $this->setCsrf();
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
     * Set a CSRF token.
     * @throws Exception
     */
    private function setCsrf(): void
    {
        try {
            if (isset($_SESSION['csrf']) === false || $this->isCsrfExpired()) {
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
                $_SESSION['csrf-expire'] = time() + 3600;
            }
        } catch (Exception $exception) {
            $this->setCsrf();
        }
    }

    /**
     * Check whether CSRF token is expired.
     *
     * @return bool
     */
    public function isCsrfExpired(): bool
    {
        return $this->get('csrf-expire') <= time();
    }

    /**
     * Set a flash message.
     *
     * @param string $key
     * @param array|string $message
     */
    public function setFlashMessage(string $key, array|string $message): void
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
     * @return array|string
     */
    public function getFlashMessage(string $key): array|string
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
        return empty($_SESSION[self::FLASH_KEY]) === false;
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