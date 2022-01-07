<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService extends AbstractLayer
{
    protected $customer;

    /**
     * This used to process request
     *
     * @param $request
     * @return void
     */
    public function run($request)
    {
        $this->customer = $request;
        return $this->createCustomer();
    }

    /**
     * This used to create customer
     * on create customer Customer Model has responsibility to send mail through Customer Observer
     * @return mixed
     */
    private function createCustomer()
    {
        return Customer::create($this->customer);
    }

}
