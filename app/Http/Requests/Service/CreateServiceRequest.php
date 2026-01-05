<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_category' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'opening_hours' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1024',
            'status' => 'nullable|string|max:50',
        ];
    }
}
