<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HttpException extends Exception
{
    protected $statusCode;

    /**
     * Constructor.
     *
     * @param string $message
     * @param int $statusCode
     */
    public function __construct($message = 'Error', $statusCode = 500)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], $this->statusCode);
    }
}
