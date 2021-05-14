<?php

namespace App\Repository;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerRepository {

    /**
     * Registra o usuário na tabela customers
     * @param  Customer  $data
     * 
     * @return Customer
     */
    public function create(Customer $data) :Customer
    {
        $customer = Customer::create($data->getAttributes());
        
        return $customer;
    }

    /**
     * Retorna todos as entidades da tabela customers
     * 
     * @return LengthAwarePaginator
     */
    public function findAll() :LengthAwarePaginator
    {
        return Customer::paginate(20);
    }

    /**
     * Busca o customer através do ID
     * @param  int  $id
     * 
     * @return Customer
     */
    public function find(Int $id) :?Customer
    {
        return Customer::find($id);
    }

    /**
     * Atualiza o usuário na tabela customers
     * @param Customer $data
     * @param Int $id
     * @return Customer
     */
    public function update(Customer $data, Int $id) :Customer
    {
        Customer::find($id)->update($data->getAttributes());
        return $data; 
    }

    /**
     * Deleta o customer através do ID
     * @param  int  $id
     * 
     */
    public function delete(Int $id) :void
    {
        Customer::destroy($id);
    }
}