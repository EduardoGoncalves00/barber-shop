<?php

namespace App\Http\Requests\Barber;

use Illuminate\Foundation\Http\FormRequest;

class GetAvailableTimesOfBarberRequest extends FormRequest
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
            'barber_id' => 'required|int',
            'service_id' => 'required|int',
            'selected_day' => 'required|string'
        ];
    }
}
