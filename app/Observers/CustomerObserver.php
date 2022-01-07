<?php

namespace App\Observers;

use App\Mail\WelcomeNewCustomer;
use App\Models\Customer;

class CustomerObserver
{
    /**
     * @var object
     */
    protected $customer;

    /**
     * After customer create sending email using Mailable
     *
     * @param Customer $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        $this->customer = $customer;
        Mail::to($customer->email)->send(new WelcomeNewCustomer($this->customer));
    }
}
