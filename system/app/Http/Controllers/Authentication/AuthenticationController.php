<?php
namespace App\Http\Controllers\Authentication;

use App\Exceptions\IncorrectCredentialsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Responses\ApiResponseError;
use App\Http\Responses\ApiResponseSuccess;
use App\Services\Authentication\AuthenticationService;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return mixed
     *
     * @throws IncorrectCredentialsException
     * @throws Exception
     */
    public function login(LoginRequest $request): mixed
    {   
        try {
            $token = app(AuthenticationService::class)->login($request->only(['email', 'password']));

            return new ApiResponseSuccess("Success when logging in.", ['token' => $token], 200);
        } catch (IncorrectCredentialsException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Incorrect credentials.", 400);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when logging in.", 400);
        }
    }

    /**
     * @return mixed
     *
     * @throws Exception
     */
    public function logout(): mixed
    {   
        try {
            app(AuthenticationService::class)->logout();

            return new ApiResponseSuccess("Success when logout.", 200);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when logout.", 400);
        }
    }
}
