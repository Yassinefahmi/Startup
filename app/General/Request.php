<?php


namespace App\General;


use App\Traits\Validations;
use JetBrains\PhpStorm\Pure;

class Request
{
    use Validations;

    /**
     * @var array|string[]
     */
    private array $errorMessages = [
        'required' => 'This field is required.',
        'string' => 'This field is not a string.',
        'integer' => 'This field is not an integer.',
        'email' => 'This field is not an email.',
        'min' => 'Minimal length of this field must be {min}.',
        'max' => 'Maximal length of this field must be {max}.',
        'confirmed' => 'The given passwords are not matching.'
    ];
    /**
     * @var array
     */
    private array $errors = [];

    /**
     * Get the path of the request.
     *
     * @return string
     */
    #[Pure] public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    /**
     * Get the method of the request.
     *
     * @return string
     */
    #[Pure] public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the body of browser client.
     *
     * @return array
     */
    #[Pure] public function getBody(): array
    {
        $body = [];

        switch ($this->getMethod()) {
            case 'get':
                foreach ($_GET as $key => $value) {
                    $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
            case 'post';
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
                break;
        }

        return $body;
    }

    /**
     * Check whether the key exists in the body of browser client.
     *
     * @param string $key
     * @return bool
     */
    #[Pure] public function inputExist(string $key): bool
    {
        return isset($this->getBody()[$key]);
    }

    /**
     * Get body of the given key.
     *
     * @param string $key
     * @return string
     */
    #[Pure] public function input(string $key): string
    {
        return $this->getBody()[$key];
    }

    /**
     * Validate body with the given validation rules.
     *
     * @param array $rules
     * @return bool
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $attribute => $validationRules) {
            $bodyValue = $this->input($attribute);

            foreach ($validationRules as $rule) {
                switch ($rule) {
                    case 'required':
                        if ($this->required($bodyValue) === false) {
                            $this->setError($attribute, $rule);
                        }
                        break;
                    case 'string':
                        if (is_string($bodyValue) === false) {
                            $this->setError($attribute, $rule);
                        }
                        break;
                    case 'integer':
                        if (is_integer($bodyValue) === false) {
                            $this->setError($attribute, $rule);
                        }
                        break;
                    case 'email':
                        if ($this->email($bodyValue) === false) {
                            $this->setError($attribute, $rule);
                        }
                        break;
                    case str_contains($rule, 'min'):
                        $amount = substr($rule, strpos($rule, ':') + 1);
                        if ($this->min($bodyValue, $amount) === false) {
                            $this->setError($attribute, $rule, [$rule => $amount]);
                        }
                        break;
                    case str_contains($rule, 'max'):
                        $amount = substr($rule, strpos($rule, ':') + 1);
                        if ($this->max($bodyValue, $amount) === false) {
                            $this->setError($attribute, $rule, [$rule => $amount]);
                        }
                        break;
                    case 'confirmed':
                        $passwordConfirmation = $this->inputExist('password_confirmation')
                            ? $this->input('password_confirmation')
                            : '';

                        if ($this->confirmed($bodyValue, $passwordConfirmation) === false) {
                            $this->setError($attribute, $rule);
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Set an error if an attribute does not meet a validation rule.
     *
     * @param string $attribute
     * @param string $rule
     * @param array $placeholders
     */
    private function setError(string $attribute, string $rule, $placeholders = []): void
    {
        if (str_contains($rule, ':')) {
            $rule = substr($rule, 0, strpos($rule, ":"));
        }

        $errorMessage = $this->errorMessages[$rule] ?? 'This rule has no error message';

        foreach ($placeholders as $key => $value) {
            $errorMessage = str_replace("{{$rule}}", $value, $errorMessage);
        }

        $this->errors[$attribute][] = $errorMessage;
    }

    /**
     * Get all errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get all available error messages.
     *
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }

    public function __destruct()
    {
        if (empty($this->errors) === false) {
            Application::$app->getSession()->setFlashMessage('validationErrors', $this->errors);
        }
    }
}