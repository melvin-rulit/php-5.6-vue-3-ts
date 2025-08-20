<?php

namespace App\Http\Requests;

class FormBRequest extends BaseRequest
{
    protected function rules()
    {
        return [
            'firstName'  => ['nullable'],
            'lastName'   => ['nullable'],
            'middleName' => ['nullable'],
            'birthDate'  => ['nullable'],
            'login'      => ['nullable'],
            'email'      => ['required', 'email'],
            'phone'      => ['nullable'],
        ];
    }
}