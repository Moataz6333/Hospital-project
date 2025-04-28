<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PlanSubscriptionRequest extends FormRequest
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
            'name' => ['required', 'min:3'],
            'phone' => ['required', 'max:13'],
            'age' => ['required'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'payment_method' => ['required', Rule::in(['cash', 'online'])],
            'national_id' => ['nullable', 'max:50'],
            'paid' => 'nullable',
        ];
    }
}
