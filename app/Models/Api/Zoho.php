<?php

namespace App\Models\Api;

use App\Models\Api\shared\ApiModel;
use App\Services\ZohoService;

class Zoho extends ApiModel
{
    protected array $fillable = [
        "api_domain",
        "token_type",
        "expires_in",
        "access_token",
        "refresh_token",
    ];

    public function refreshToken()
    {
        $response = (new ZohoService)->generateRefreshToken();
        $this->fill($response);
        return $this;
    }

    public function accessToken()
    {
        $response = (new ZohoService)->generateAccessToken();
        $this->fill($response);
        return $this;
    }
}
