<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use Faker\Factory;

class CustomerTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }
    
    /**
     *
     * @return void
     */
    public function test_customer_create()
    {
        $data = [
            'name' => 'Giovanna',
            'date_of_birth' => '1997-03-21',
            'cpf' => '15366616788'
        ];

        $this->post(route('customers.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_customer_update()
    {
        $customer = Customer::factory()->create();

        $data = [
            'name' => 'Giovanna',
            'date_of_birth' => '1997-03-21',
            'cpf' => '18464116799'
        ];

        $this->put(route('customers.update', $customer->id), $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function test_customer_get()
    {
        $customers = Customer::factory(3)->create()->map(function($customer) {
            return $customer->only(['id', 'name', 'cpf', 'date_of_birth']);
        });

        $this->get(route('customers.index'))
            ->assertStatus(200);
    }

    public function test_customer_show()
    {
        $customer = Customer::factory()->create();

        $this->get(route('customers.show', $customer->id))
            ->assertStatus(200);
    }

    public function test_customer_delete()
    {
        $customer = Customer::factory()->create();

        $this->delete(route('customers.destroy', $customer->id))
            ->assertStatus(200);

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_customer_create_cpf_error()
    {
        $data = [
            'name' => 'Giovanna',
            'date_of_birth' => '1997-03-21',
            'cpf' => 'cA5vXS37qaC'
        ];

        $this->post(route('customers.store'), $data)
            ->assertStatus(400);
    }

}
