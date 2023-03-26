<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_year' => ['required', 'numeric', 'min:1900', 'max:' . (date('Y') + 1)],
            'chassis' => ['required', 'string', 'max:255'],
            'vehicle_color' => ['nullable', 'string', 'max:255'],
            'vehicle_plate' => ['required', 'string', 'max:10'],
            'vehicle_make' => ['required', 'string', 'max:255'],
            'vehicle_model' => ['required', 'string', 'max:255'],
            'site_a' => ['required', 'string', 'max:255'],
            'site_b' => ['nullable', 'string', 'max:255'],
            'policy_number' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'policy_plan' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'location_url' => ['nullable', 'url', 'max:255'],
            'service' => ['required', 'string', 'max:255'],
            'zone' => ['required', 'string', 'max:255']
        ];
    }
}
