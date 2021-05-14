<?php

namespace App\Repository;

use App\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountRepository {
    
    /**
     * Registra a conta na tabela accounts
     * @param  Account $data
     * 
     * @return Account
     */
    public function create(Account $data) :Account
    {
        return Account::firstOrcreate($data->getAttributes());
    }

    /**
     * Retorna todos as entidades da tabela accounts
     * 
     * @return LengthAwarePaginator
     */
    public function findAll() :LengthAwarePaginator
    {
        return Account::paginate(20);
    }

    /**
     * Busca a account através do ID
     * @param int $id
     * 
     * @return Account|null
     */
    public function find(int $id) :?Account
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
    public function findCustomerAccount(int $customer_id, int $account_type) :Account
    {
        return Account::where('customer_id', $customer_id)->where('type', $account_type)->first();
    }

    /**
     * Atualiza a conta na tabela accounts
     * @param Account $data
     * @param int $id
     * @return Account
     */
    public function update(Account $data, Int $id) :Account
    {
        $account = Account::find($id)->fill($data->getAttributes());
        $account->save();

        return $account; 
    }

    /**
     * Deleta a conta através do ID
     * @param  int  $id
     * 
     */
    public function delete(int $id) :void
    {
        Account::destroy($id);
    }

}