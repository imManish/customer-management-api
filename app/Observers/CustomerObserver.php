<?php

namespace App\Observers;

use App\Mail\WelcomeNewCustomer;
use App\Models\Customer;

class CustomerObserver
{
    protected $customer;

    public function created(Customer $customer)
    {
        $this->customer = $customer;
        Mail::to($customer->email)->send(new WelcomeNewCustomer($this->customer));
    }
}
