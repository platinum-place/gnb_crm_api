<?php

namespace App\Http\Requests\Cases;

use Illuminate\Foundation\Http\FormRequest;

class StoreCaseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_year' => ['numeric', 'required'],
            'chassis' => ['string', 'required'],
            'vehicle_color' => ['string', 'required'],
            'vehicle_plate' => ['string', 'max:10', 'required'],
            'vehicle_make' => ['string', 'required'],
            'vehicle_model' => ['string', 'required'],
            'site_a' => ['string', 'required'],
            'site_b' => ['string', 'required'],
            'policy_number' => ['string', 'required'],
            'client_name' => ['string', 'required'],
            'phone' => ['string', 'required'],
            'policy_plan' => ['string', 'required'],
            'description' => ['string', 'required'],
            'location_url' => ['url', 'required'],
            'service' => ['string', 'required'],
            'zone' => ['string', 'required']
        ];
    }
}
