<?php


namespace Nsingularity\GeneralModule\Foundation\Http\Requests\AuthRequests;

use Nsingularity\GeneralModule\Foundation\Http\Requests\ValidationFormRequest;

class LoginValidatedRequest extends ValidationFormRequest
{
    protected function rules(): array
    {
        return [
            "username" => "required",
            "password" => "required",
        ];
    }

    protected function messages(): array
    {
        return [];
    }
}
