<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponseSuccess implements Responsable
{
    protected $message;
    protected $data;
    protected $status;

    /**
     * @param string $data
     * @param array $data
     * @param int $status
     */
    public function __construct(string $message, $data = [], int $status = 200)
    {
        $this->message = $message;
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        $responseArray = [
            'message' => $this->message
        ];

        if (!empty($this->data)) {
            $responseArray['data'] = $this->data;
        }

        return response()->json($responseArray, $this->status);
    }
}