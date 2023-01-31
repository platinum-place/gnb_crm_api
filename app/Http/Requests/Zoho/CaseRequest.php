<?php

namespace App\Http\Requests\Zoho;

use Illuminate\Foundation\Http\FormRequest;

class CaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'policy_number' => ['required', 'string'],
            'vehicle_model' => ['required', 'string'],
            'site_a' => ['required', 'string'],
            'site_b' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'policy_plan' => ['required', 'string'],
            'vehicle_make' => ['required', 'string'],
            'vehicle_plate' => ['required', 'string'],
            'vehicle_color' => ['required', 'string'],
            'service' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'chassis' => ['required', 'string'],
            'client_name' => ['required', 'string'],
            'vehicle_year' => ['required', 'integer'],
            'location_url' => ['required', 'regex:/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([-.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'],
        ];
    }
}
