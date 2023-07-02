<?php

namespace App\Http\Resources;

use App\Http\Resources\shared\ZohoCaseResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ZohoCaseCollectionResource extends JsonResource
{
    use ZohoCaseResourceTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->case();
    }
}
