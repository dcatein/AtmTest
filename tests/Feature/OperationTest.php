<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Account;

class OperationTest extends TestCase
{
    public function test_deposit()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500
        ];

        $this->post(route('operation.deposit'), $data)
            ->assertStatus(200);
    }

    public function test_deposit_error()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500.55
        ];

        $this->post(route('operation.deposit'), $data)
            ->assertStatus(400);
    }

    public function test_withdraw_500()
    {
        $account = Account::factory()->create();

        $data = [
            'account_id' => $account->id,
            'value' => 500
        ];

        $this->post(route('operation.withdraw'), $data)
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

        $this->post(route('operation.withdraw'), $data)
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

        $this->post(route('operation.withdraw'), $data)
            ->assertStatus(400);
    }
}
