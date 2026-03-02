<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public function __construct(string $message, int $code = 400, array $errors = [])
    {
        parent::__construct([
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
        ]);
    }

    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'message' => $this->resource['message'],
            'errors' => $this->resource['errors'],
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)->setStatusCode($this->resource['code']);
    }
}
