<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeNewCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var object
     */
    public $data;

    /**
     * @var string
     */
    public $subject = 'Welcome on board!';

    /**
     * @var string
     */
    public $message = 'Welcome! onboard with us.hope you will enjoy the platfrom';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer)
    {
        $this->data = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->Subject)
            ->view('mail.customer.new', [
                'body' => $this->Message
            ])
            ->from(env('MAIL_FROM_ADDRESS'))
            ->to($this->data->email);
    }
}
