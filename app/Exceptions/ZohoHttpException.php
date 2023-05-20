<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ZohoHttpException extends Exception
{
    protected array $data;

    /**
     * Constructor.
     *
     * @param array $data
     * @param string $message
     */
    public function __construct(array $data, string $message)
    {
        parent::__construct($message);
        $this->data = $data;
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
            'more_info' => $this->data
        ], 400);
    }
}
