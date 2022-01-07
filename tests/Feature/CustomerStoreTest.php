<?php

namespace Tests\Feature;

use App\Mail\WelcomeNewCustomer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CustomerStoreTest extends TestCase
{
    /**
     *
     * @var string
     */
    protected $baseUrl;

    /**
     *
     * @var string
     */
    protected $apiPrifix = '/api';

    /**
     *
     * @var string
     */
    protected $endPoint = '/customer';

    /**
     * @var string
     */
    protected $header;

    /**
     * @var array
     */
    protected $formData;

    /**
     * set baseUrl
     */
    public function __construct()
    {
        $this->baseUrl = $this->apiPrifix . $this->endPoint;
        $this->header = [
            'accept' => 'application/json'
        ];
        parent::__construct();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testForCreatingNewCustomer()
    {
        $this->formData = [ "name" => "manish", "email" =>  "project.developer1@gmail.com"];
        $response = $this->post($this->baseUrl, $this->formData, $this->header);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testForSendingEmailThrougWelcomeNewCustomerMail()
    {
        Mail::fake();
        $this->user = User::find(1);
        Mail::assertSent(new WelcomeNewCustomer($this->user));
    }
}
