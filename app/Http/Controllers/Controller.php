<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function success($data = null, string $message = '', int $code = 200)
{
    return response()->json([
        'status' => 'success',
        'message' => $message,
        'data' => $data,
    ], $code);
}

protected function error(string $message, int $code = 400, $errors = null)
{
    return response()->json([
        'status' => 'error',
        'message' => $message,
        'errors' => $errors,
    ], $code);
}
}
