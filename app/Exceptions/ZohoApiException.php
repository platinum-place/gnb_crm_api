<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ZohoApiException extends HttpException
{
    public function __construct(array $data)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, json_encode($data));
    }

    public function render($request)
    {
        if ($request->wantsJson()) {
            return new JsonResponse(
                json_decode($this->getMessage(), true),
                $this->getStatusCode(),
                $this->getHeaders(),
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            );
        }

        return App::abort($this->getStatusCode(), $this->getMessage(), $this->getHeaders());
    }
}
