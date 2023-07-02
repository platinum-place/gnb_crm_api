<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class HttpResponse extends JsonResponse
{
    public static function delete()
    {
        return new static(['message' => __('Resource deleted.')], 222);
    }
}
