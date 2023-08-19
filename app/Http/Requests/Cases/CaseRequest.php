<?php

namespace App\Http\Requests\Cases;

use App\Http\Requests\shared\RequestUtilitiesTrait;
use Illuminate\Foundation\Http\FormRequest;

class CaseRequest extends FormRequest
{
    public array $map = [
        "P_liza" => "policy_number",
        "Chasis" => "chassis",
        "A_o" => "vehicle_year",
        "Color" => "vehicle_color",
        "Marca" => "vehicle_make",
        "Modelo" => "vehicle_model",
        "Placa" => "vehicle_plate",
        "Punto_A" => "site_a",
        "Punto_B" => "site_b",
        "Solicitante" => "client_name",
        "Phone" => "phone",
        "Plan" => "policy_plan",
        "Description" => "description",
        "Ubicaci_n" => "location_url",
        "Tipo_de_asistencia" => "service",
        "Zona" => "zone",
        "Asegurado" => "client_name",
    ];

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
            'vehicle_year' => ['numeric'],
            'chassis' => ['string'],
            'vehicle_color' => ['string'],
            'vehicle_plate' => ['string', 'max:10'],
            'vehicle_make' => ['string'],
            'vehicle_model' => ['string'],
            'site_a' => ['string'],
            'site_b' => ['string'],
            'policy_number' => ['string'],
            'client_name' => ['string'],
            'phone' => ['string'],
            'policy_plan' => ['string'],
            'description' => ['string'],
            'location_url' => ['url'],
            'service' => ['string'],
            'zone' => ['string']
        ];
    }

    public function prepareForValidation()
    {
        $data = $this->all();
        foreach ($this->map as $key => $value) {
            if (isset($data[$value]) && !is_null($value)) {
                $data[$key] = $data[$value];
                unset($data[$value]);
            }
        }
        $this->replace($data);
    }

    public function requiredRules(array $except = []): array
    {
        $rules = $this->rules();
        foreach ($rules as $field => &$fieldRules) {
            if (in_array('required', $fieldRules))
                continue;

            if (in_array($field, $except))
                continue;

            $fieldRules[] = 'required';
        }
        return $rules;
    }
}
