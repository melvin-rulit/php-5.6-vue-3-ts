<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormARequest;
use App\Http\Requests\FormBRequest;

class FormController
{
    public function handleFormA()
    {
        $request = new FormARequest();

        if (!$request->validate()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $request->errors()]);
            return;
        }

        $applicationId = rand(1000, 9999);
        echo json_encode(['success' => true, 'application_id' => $applicationId, 'classifier' => 'FORM_A']);
    }

    public function handleFormB()
    {
        $request = new FormBRequest();

        if (!$request->validate()) {
            http_response_code(400);
            echo json_encode(['success' => false, 'errors' => $request->errors()]);
            return;
        }

        $applicationId = rand(1000, 9999);
        echo json_encode(['success' => true, 'application_id' => $applicationId, 'classifier' => 'FORM_B']);
    }
}