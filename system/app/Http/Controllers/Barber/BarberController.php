<?php
namespace App\Http\Controllers\Barber;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Barber\CreateBarberRequest;
use App\Http\Requests\Barber\UpdateBarberRequest;
use App\Services\Barber\BarberService;
use App\Http\Responses\ApiResponseSuccess;
use App\Http\Responses\ApiResponseError;
use Exception;
use Illuminate\Support\Facades\Log;

class BarberController extends Controller
{
    /**
     * @param CreateBarberRequest $request
     * @return mixed
     * 
     * @throws EmailAlreadyRegisteredException
     * @throws Exception
     */
    public function create(CreateBarberRequest $request): mixed
    {   
        try {
            app(BarberService::class)->create($request->only(['name', 'email', 'phone', 'password', 'start_work', 'end_work', 'start_lunch', 'end_lunch']));

            return new ApiResponseSuccess("Success when creating.", 200);
        } catch (EmailAlreadyRegisteredException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("E-mail already registered.", 400);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when creating.", 400);
        } 
    }

    /**
     * @param UpdateBarberRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function update(UpdateBarberRequest $request): mixed
    {
        try {
            app(BarberService::class)->update($request->only(['name', 'email', 'phone', 'password', 'start_work', 'end_work', 'start_lunch', 'end_lunch']));

            return new ApiResponseSuccess("Success when updating.", 200);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when updating.", 400);
        }
    }
}
