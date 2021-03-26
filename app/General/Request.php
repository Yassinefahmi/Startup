<?php


namespace App\General;


use App\Traits\Validations;

class Request
{
    use Validations;

    private array $errors = [];

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isMethod(string $method): bool
    {
        return $this->getMethod() === $method;
    }

    public function getBody(): array
    {
        $body = [];

        if ($this->isMethod('get')) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->isMethod('post')) {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    public function inputExist(string $key): bool
    {
        return isset($this->getBody()[$key]);
    }

    public function input(string $key): string
    {
        return $this->getBody()[$key];
    }

    public function validate(array $rules):bool
    {
        foreach ($rules as $attribute => $validationRules) {
            $bodyValue = $this->input($attribute);

            foreach ($validationRules as $rule) {
                switch ($rule) {
                    case 'required':
                        if ($this->required($bodyValue) === false) {
                            $this->addError($attribute, $rule);
                        }
                        break;
                    case 'string':
                        if (is_string($bodyValue) === false) {
                            $this->addError($attribute, $rule);
                        }
                        break;
                    case 'integer':
                        if (is_integer($bodyValue) === false) {
                            $this->addError($attribute, $rule);
                        }
                        break;
                    case 'email':
                        if ($this->email($bodyValue) === false) {
                            $this->addError($attribute, $rule);
                        }
                        break;
                    case str_contains($rule, 'min'):
                        $amount = substr($rule, strpos($rule, ':') + 1);
                        if ($this->min($bodyValue, $amount) === false) {
                            $this->addError($attribute, $rule, [$rule => $amount]);
                        }
                        break;
                    case str_contains($rule, 'max'):
                        $amount = substr($rule, strpos($rule, ':') + 1);
                        if ($this->max($bodyValue, $amount) === false) {
                            $this->addError($attribute, $rule, [$rule => $amount]);
                        }
                        break;
                    case 'confirmed':
                        $passwordConfirmation = $this->inputExist('password_confirmation')
                            ? $this->input('password_confirmation')
                            : '';

                        if ($this->confirmed($bodyValue, $passwordConfirmation) === false) {
                            $this->addError($attribute, $rule);
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    private function addError(string $attribute, string $rule, $placeholders = []): void
    {
        if (str_contains($rule, ':')) {
            $rule = substr($rule, 0, strpos($rule, ":"));
        }

        $errorMessage = $this->errorMessages()[$rule] ?? 'This rule has no error message';

        foreach ($placeholders as $key => $value) {
            $errorMessage = str_replace("{{$rule}}", $value, $errorMessage);
        }

        $this->errors[$attribute][] = $errorMessage;
    }

    public function errorMessages(): array
    {
        return [
            'required' => 'This field is required',
            'string' => 'This field is not a string',
            'integer' => 'This field is not an integer',
            'email' => 'This field is not an email',
            'min' => 'Minimal length of this field must be {min}',
            'max' => 'Maximal length of this field must be {max}',
            'confirmed' => 'The given passwords are not matching'
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}