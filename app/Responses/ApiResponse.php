<?php

namespace App\Responses;

class ApiResponse
{

    public bool $success;
    public string $message;
    public array $data = [];
    public int $code;

    public function __construct(bool $success, string $message, array $data = [], int $code = 200)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->code = $code;
    }

    public function toArray()
    {
        return [
            'success' => $this->success,
            'msg' => $this->message,
            'data' => $this->data,
        ];
    }

    public function toResponse()
    {
        return response()->json(
            $this->toArray(),
            $this->code
        );
    }

    public static function success(string $message, array $data = [], int $code = 200): \Illuminate\Http\JsonResponse
    {
        return (new self(true, $message, $data, $code))->toResponse();
    }

    public static function fail(string $message, array $data = [], int $code = 400): \Illuminate\Http\JsonResponse
    {
        return (new self(false, $message, $data, $code))->toResponse();
    }
}