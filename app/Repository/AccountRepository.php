<?php

namespace App\Repository;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountRepository {
    
    /**
     * Registra a conta na tabela accounts
     * @param  Account $data
     * 
     * @return Account
     */
    public function create(Account $data)
    {
        return Account::create($data->getAttributes());
    }

    /**
     * Retorna todos as entidades da tabela accounts
     */
    public function findAll()
    {
        $model = new Account();
        return DB::table($model->getTable())->paginate(20);
    }

    /**
     * Busca a account através do ID
     * @param int $id
     * 
     * @return Account
     */
    public function find(Int $id)
    {
        return Account::find($id);
    }

    /**
     * Busca a account através do customer_id e type
     * @param int $customer_id
     * @param int $account_type
     * 
     * @return Account
     */
    public function findCustomerAccount(Int $customer_id, Int $account_type)
    {
        return Account::where('customer_id', $customer_id)->where('type', $account_type)->first();
    }

    /**
     * Atualiza a conta na tabela accounts
     * @param Account $data
     * @param Int $id
     * @return Account
     */
    public function update(Account $data, Int $id)
    {
        return Account::where('id', $id)->update($data->getAttributes());
    }

    /**
     * Deleta a conta através do ID
     * @param  int  $id
     * 
     */
    public function delete($id)
    {
        return Account::destroy($id);
    }

}