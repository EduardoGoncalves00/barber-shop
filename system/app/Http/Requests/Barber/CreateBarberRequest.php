<?php

namespace App\Http\Requests\Barber;

use Illuminate\Foundation\Http\FormRequest;

class CreateBarberRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'start_lunch' => 'required|date_format:H:i',
            'end_lunch' => 'required|date_format:H:i',
            'start_work' => 'required|date_format:H:i',
            'end_work' => 'required|date_format:H:i', 
        ];
    }
}
