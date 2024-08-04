<?php
namespace App\Http\Controllers\Customer;

use App\Exceptions\EmailAlreadyRegisteredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Barber\GetScheduleAvailableBarberRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Http\Responses\ApiResponseSuccess;
use App\Http\Responses\ApiResponseError;
use App\Services\Customer\GetAvailableTimesOfBarberService;
use App\Services\Customer\CustomerService;
use Exception;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * @param CreateCustomerRequest $request
     * @return mixed
     * 
     * @throws EmailAlreadyRegisteredException
     * @throws Exception
     */
    public function create(CreateCustomerRequest $request): mixed
    {   
        try {
            app(CustomerService::class)->create($request->only(['name', 'phone', 'email', 'password']));

            return new ApiResponseSuccess("Success when creating.", 200);
        } catch (EmailAlreadyRegisteredException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("E-mail already registered", 400);
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
     * @param UpdateCustomerRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function update(UpdateCustomerRequest $request): mixed
    {
        try {
            app(CustomerService::class)->update($request->only(['name', 'email', 'phone', 'password']));

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

    /**
     * @param GetScheduleAvailableBarberRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function getScheduleAvailableBarber(GetScheduleAvailableBarberRequest $request): mixed
    {
        try {
            return new ApiResponseSuccess(
                "Success when get schedule barber.",
                app(GetAvailableTimesOfBarberService::class)->getScheduleAvailableBarber($request->only(['barber_id', 'service_id', 'selected_day'])),
                200
            );
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when get schedule barber.", 400);
        }
    }
}
