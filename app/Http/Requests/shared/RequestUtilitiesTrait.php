<?php

namespace App\Http\Requests\shared;

trait RequestUtilitiesTrait
{
    public function requiredRules(array $except = []): array
    {
        $rules = $this->rules();
        foreach ($rules as $field => &$fieldRules) {
            if (in_array('required', $fieldRules)) {
                continue;
            }

            if (in_array($field, $except)) {
                continue;
            }

            $fieldRules[] = 'required';
        }

        return $rules;
    }

    private function mapRequest(array $map)
    {
        $data = $this->all();
        foreach ($map as $key => $value) {
            if (isset($data[$value]) && ! is_null($value)) {
                $data[$key] = $data[$value];
                unset($data[$value]);
            }
        }
        $this->replace($data);
    }
}
