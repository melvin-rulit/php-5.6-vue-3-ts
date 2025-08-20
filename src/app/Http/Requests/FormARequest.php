<?php

namespace App\Http\Requests;

class FormARequest extends BaseRequest
{
    protected function rules()
    {
        return [
            'name'  => ['required'],
            'inn'   => ['required'],
            'email' => ['email'], // не обязательный, но должен быть валидным если заполнен
            'phone' => ['phone']  // если есть — должен быть корректным
        ];
    }

}