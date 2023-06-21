<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ResponseJson extends JsonResponse
{
    public static function message(string $message, int $code = 200)
    {
        return new static(['message' => $message], $code);
    }
}
