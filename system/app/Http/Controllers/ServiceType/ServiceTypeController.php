<?php
namespace App\Http\Controllers\ServiceType;

use App\Exceptions\ServiceTypeDoesNotExistException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceType\CreateServiceTypeRequest;
use App\Http\Requests\ServiceType\UpdateServiceTypeRequest;
use App\Http\Responses\ApiResponseSuccess;
use App\Http\Responses\ApiResponseError;
use App\Services\ServiceType\ServiceTypeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceTypeController extends Controller
{
    /**
     * @return mixed
     * 
     * @throws Exception
     */
    public function index(): mixed
    {   
        try {
            $services = app(ServiceTypeService::class)->index();

            return new ApiResponseSuccess("Success in returning the type of service.", $services, 201);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error in returning the type of service.", 400);
        } 
    }

    /**
     * @param CreateServiceTypeRequest $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function create(CreateServiceTypeRequest $request): mixed
    {   
        try {
            app(ServiceTypeService::class)->create($request->only(['service_name', 'value', 'estimated_time']));

            return new ApiResponseSuccess("Success when creating.", 201);
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
     * @param Request $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function update(UpdateServiceTypeRequest $request): mixed
    {   
        try {
            app(ServiceTypeService::class)->update($request->only(['id', 'service_name', 'value', 'estimated_time']));

            return new ApiResponseSuccess("Success when update.", 201);
        } catch (ServiceTypeDoesNotExistException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Type of service does not exist.", 400);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when update.", 400);
        } 
    } 

    /**
     * @param Request $request
     * @return mixed
     * 
     * @throws Exception
     */
    public function delete(Request $request): mixed
    {   
        try {
            app(ServiceTypeService::class)->delete($request->id);

            return new ApiResponseSuccess("Success when delete.", 201);
        } catch (ServiceTypeDoesNotExistException $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Type of service does not exist.", 400);
        } catch (Exception $e) {
            Log::error(__METHOD__, [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return new ApiResponseError("Error when delete.", 400);
        } 
    } 
}