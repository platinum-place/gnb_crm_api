<?php

namespace App\Http\Resources;

use App\Http\Resources\shared\ZohoCaseResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZohoCaseResource extends JsonResource
{
    use ZohoCaseResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $case = $this->case();
        $case['service_info']['location'] = $this->getLocation();
        return $case;
    }
}
