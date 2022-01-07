<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * @var string
     */
    protected $message= '';

    /**
     * @var array
     */
    protected $response= [];

    /**
     * @var int
     */
    protected $status= Response::HTTP_OK;

    /**
     * @var string
     */
    protected $startExecutionTime;

    /**
     * This used to initiate instances
     */
    public function __construct()
    {
        $this->startExecutionTime = microtime(true);
    }

    /**
     * This used to store new customer
     *
     * @param CustomerRequest $request
     * @param CustomerService $customer
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function store(CustomerRequest $request, CustomerService $customer)
    {
        try{
            $this->response = $customer->run($request->all());
            $this->message = __('New customer created successfully.');
            return  $this->sendResponse(
                $this->message,
                $this->status,
                $this->response,
                $this->executionTime($this->startExecutionTime, $this->calculateTime())
            );
        } catch (\Exception $e)
        {
            return $this->apiException($e);
        }

    }

}
