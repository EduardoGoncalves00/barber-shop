<?php

namespace App\Http\Requests\ServiceType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'int|required',
            'service_name' => 'string',
            'value' => 'numeric',
            'estimated_time' => 'date_format:H:i',
        ];
    }
}
