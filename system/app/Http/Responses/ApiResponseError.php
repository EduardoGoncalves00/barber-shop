<?php
namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponseError implements Responsable
{
    protected $message;
    protected $status;

    /**
     * @param string $message
     * @param int $status
     */
    public function __construct($message, $status = 400)
    {
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->status);
    }
}
