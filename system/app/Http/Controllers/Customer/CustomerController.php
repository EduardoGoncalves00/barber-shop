<?php
namespace App\Http\Controllers\Customer;

use App\Exceptions\BarberDoesNotExistException;
use App\Exceptions\EmailAlreadyRegisteredException;
use App\Exceptions\SelectedDayInvalidException;
use App\Exceptions\ServiceTypeDoesNotExistException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Barber\GetAvailableTimesOfBarberRequest;
use App\Http\Requests\Barber\GetScheduleAvailableBarberRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Http\Requests\Customer\MakeReserveRequest;
use App\Http\Responses\ApiResponseSuccess;
use App\Http\Responses\ApiResponseError;
use App\Services\Customer\GetAvailableTimesOfBarberService;
use App\Services\Customer\CustomerService;
use App\Services\Customer\MakeReserveService;
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

            return new ApiResponseSuccess("Success when creating.", 201);
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
     * @param UpdateCustomerRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function update(UpdateCustomerRequest $request): mixed
    {
        try {
            app(CustomerService::class)->update($request->only(['name', 'phone', 'password']));

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
     * @param GetAvailableTimesOfBarberRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function GetAvailableTimesOfBarber(GetAvailableTimesOfBarberRequest $request): mixed
    {
        try {
            return new ApiResponseSuccess(
                "Success when get available times of barber.",
                app(GetAvailableTimesOfBarberService::class)->getTimes($request->only(['barber_id', 'service_id', 'selected_day'])),
                200
            );
        } catch (BarberDoesNotExistException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Informed barber does not exist.", 400);
        } catch (ServiceTypeDoesNotExistException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Informed service type does not exist.", 400);
        } catch (SelectedDayInvalidException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Selected day is old.", 400);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when get available times of barber.", 400);
        }
    }

    /**
     * @param MakeReserveRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function MakeReserve(MakeReserveRequest $request): mixed
    {
        try {
            app(MakeReserveService::class)->make($request->only(['service_id', 'selected_time', 'barber_id', 'observation']));

            return new ApiResponseSuccess(
                "Success when make reserve.",
                200
            );
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when make reserve.", 400);
        }
    }
}
