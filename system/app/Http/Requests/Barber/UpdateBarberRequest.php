<?php

namespace App\Http\Requests\Barber;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBarberRequest extends FormRequest
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
            'name' => 'string',
            'phone' => 'string',
            'password' => 'string|min:6',
            'start_lunch' => 'date_format:H:i',
            'end_lunch' => 'date_format:H:i',
            'start_work' => 'date_format:H:i',
            'end_work' => 'date_format:H:i'
        ];
    }
}
