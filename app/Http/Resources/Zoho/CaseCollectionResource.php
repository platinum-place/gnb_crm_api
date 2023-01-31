<?php

namespace App\Http\Resources\Zoho;

use Illuminate\Http\Resources\Json\JsonResource;

class CaseCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        dd($this->collection);
        //return parent::toArray($request);
        return array_merge(
            [
                self::$wrap => $this->collection,
            ],
            $request,
        );
    }
}
