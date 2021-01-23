<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Account;
use App\Models\Customer;

class AccountTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_account_create()
    {
        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'type' => 0,
            'balance' => 0
        ];

        $this->post(route('account.store'), $data)
            ->assertStatus(201)
            ->assertJson($data);
    }

    public function test_account_update()
    {
        $account = Account::factory()->create();
        $customer = Customer::factory()->create();
        
        $data = [
            'customer_id' => $customer->id,
            'type' => 1,
            'balance' => 0
        ];

        $this->put(route('account.update', $account->id), $data)
            ->assertStatus(200)
            ->assertJson($data);
    }

    public function test_account_get()
    {
        $accounts = Account::factory(3)->create()->map(function($account) {
            return $account->only(['id', 'customer_id', 'type', 'balance']);
        });

        $this->get(route('account.index'))
            ->assertStatus(200);
    }

    public function test_account_show()
    {
        $account = Account::factory()->create();

        $this->get(route('account.show', $account->id))
            ->assertStatus(200);
    }

    public function test_account_delete()
    {
        $account = Account::factory()->create();

        $this->delete(route('account.destroy', $account->id))
            ->assertStatus(200);
    }

    public function test_account_type_error()
    {
        $account = Account::factory()->create();
        $customer = Customer::factory()->create();
        
        $data = [
            'customer_id' => $customer->id,
            'type' => 3,
            'balance' => 0
        ];

        $this->post(route('account.store'), $data)
            ->assertStatus(400);
    }

    public function test_deposit()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500
        ];

        $this->post(route('account.deposit'), $data)
            ->assertStatus(200);
    }

    public function test_deposit_error()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500.55
        ];

        $this->post(route('account.deposit'), $data)
            ->assertStatus(400);
    }

    public function test_withdraw_500()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500
        ];

        $this->post(route('account.withdraw'), $data)
            ->assertStatus(200)
            ->assertJson([
                100 => 5
            ]);
    }

    public function test_withdraw_370()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 370
        ];

        $this->post(route('account.withdraw'), $data)
            ->assertStatus(200)
            ->assertJson([
                100 => 3,
                50 => 1,
                20 => 1
            ]);
    }

    public function test_withdraw_error()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500.55
        ];

        $this->post(route('account.withdraw'), $data)
            ->assertStatus(400);
    }
}
