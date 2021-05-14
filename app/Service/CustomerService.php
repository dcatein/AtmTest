<?php

namespace App\Service;

use App\Repository\CustomerRepository;
use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerService {

     /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    public function __construct(
        CustomerRepository $customerRepository
    ){
        $this->customerRepository = $customerRepository;
    }

    /**
     * Registra o usuário na tabela customers
     * @param Customer $entity
     * 
     * @return Customer
     */
    public function create(Customer $entity) :Customer
    {
        try {
            DB::beginTransaction();
            $customer = $this->customerRepository->create($entity);
            DB::commit();
            return $customer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }   
        
    }

    /**
     * 
     * @return LengthAwarePaginator
     */
    public function findAll() :LengthAwarePaginator
    {
        return $this->customerRepository->findAll();
    }

    /**
     * Busca o customer através do ID
     * @param  int  $id
     * 
     * @return Customer
     */
    public function find(int $id) :?Customer
    {
        return $this->customerRepository->find($id);
    }

    /**
     * Atualiza o usuário na tabela customers
     * @param  Customer  $data
     * @param int $id 
     * 
     * @return Customer
     */
    public function update(Customer $data, int $id) :Customer
    {
        try {
            DB::beginTransaction();
            $customer = $this->customerRepository->update($data, $id);
            DB::commit();
            return $customer;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }   
    }

    /**
     * Busca o customer através do ID
     * @param  int  $id
     * 
     */
    public function delete(int $id) :void
    {
        try {
            DB::beginTransaction();
            $this->customerRepository->delete($id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

     /**
     * Preenche a entidade através de um array
     * @param  Array  $data
     * 
     * @return Customer
     */
    public function fillEntity(Array $data) :Customer
    {
        return new Customer($data);
    }
}
