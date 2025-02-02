<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $e->errors()
                ], 422);
            }

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        });
    }
}
