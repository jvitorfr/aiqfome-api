<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function rules(): array
    {
        $clientId = $this->route('client')->id ?? null;

        return [
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:clients,email,' . $clientId,
            'password' => 'nullable|string|min:6',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
