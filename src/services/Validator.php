<?php

namespace App\services;

use App\exceptions\ValidationException;

class Validator 
{
    private array $errors = [];

    public function validate(array $data, array $rules): bool 
    {
        $this->errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $rulesArray = explode('|', $ruleString);

            foreach ($rulesArray as $rule) {
                $rule = trim($rule);

                $ruleName = $rule;
                $paramString = '';

                if (str_contains($rule, ':')) {
                    [$ruleName, $paramString] = explode(':', $rule, 2);
                }

                if ($ruleName === 'required') {
                    if ($value === null || (is_string($value) && trim($value) === '')) {
                        $this->addError($field, "The {$field} field is required.");
                        break;
                    }
                }

                if ($value === null || $value === '') {
                    continue;
                }

                if ($ruleName === 'in') {
                    $allowedValues = array_map('trim', explode(',', $paramString));

                    if (!in_array((string)$value, $allowedValues, true)) {
                        $allowedList = implode(', ', $allowedValues);
                        $this->addError($field, "The {$field} field must be one of the following: {$allowedList}.");
                    }
                }

                if ($ruleName === 'integer') {
                    if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                        $this->addError($field, "The {$field} field must be an integer.");
                    }
                }

                if ($ruleName === 'float') {
                    if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
                        $this->addError($field, "The {$field} field must be a valid decimal number (float).");
                    }
                }

                if ($ruleName === 'string') {
                    if (!is_string($value)) {
                        $this->addError($field, "The {$field} field must be a valid string.");
                    }
                }
            }
        }

        return empty($this->errors);
    }

    public function validateOrFail(array $data, array $rules): void 
    {
        if (!$this->validate($data, $rules)) {
            throw new ValidationException($this->errors);
        }
    }

    private function addError(string $field, string $message): void 
    {
        $this->errors[$field][] = $message;
    }
}