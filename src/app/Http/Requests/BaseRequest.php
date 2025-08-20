<?php

namespace App\Http\Requests;

class BaseRequest
{
    protected $data;
    protected $errors = [];

    public function __construct()
    {
        $input = file_get_contents('php://input');
        $this->data = json_decode($input, true) ?: [];
    }

    public function all()
    {
        return $this->data;
    }

    public function validate()
    {
        $rules = $this->rules();

        foreach ($rules as $field => $validators) {
            $value = isset($this->data[$field]) ? trim($this->data[$field]) : null;

            foreach ($validators as $rule) {
                if ($rule === 'required' && !$value) {
                    $this->errors[$field][] = 'Поле обязательно';
                }
                if ($rule === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = 'Некорректный email';
                }
                if ($rule === 'phone' && $value && !preg_match('/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/', $value)) {
                    $this->errors[$field][] = 'Некорректный формат телефона';
                }
            }
        }

        return empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    protected function rules()
    {
        return [];
    }
}