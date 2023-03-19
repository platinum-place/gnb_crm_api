<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{
    private $user;
    private $token;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->user = $resource[0];
        $this->token = $resource[1];
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => $this->user->toArray(),
            'token' => $this->token->plainTextToken,
        ];
    }
}
