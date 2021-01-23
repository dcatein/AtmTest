<?php

namespace App\Service;

use App\Repository\CustomerRepository;
use App\Models\Customer;

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
    public function create(Customer $entity)
    {   
        $customer = $this->customerRepository->create($entity);
        return $customer;
    }

    
    public function findAll()
    {
        return $this->customerRepository->findAll();
    }

    /**
     * Busca o customer através do ID
     * @param  int  $id
     * 
     * @return Customer
     */
    public function find(Int $id)
    {
        return $this->customerRepository->find($id);
    }

    /**
     * Atualiza o usuário na tabela customers
     * @param  Customer  $data
     * 
     * @return Customer
     */
    public function update(Customer $data, $id)
    {
        return $this->customerRepository->update($data, $id);
    }

    /**
     * Busca o customer através do ID
     * @param  int  $id
     * 
     */
    public function delete(Int $id)
    {
       $this->customerRepository->delete($id);
    }

     /**
     * Preenche a entidade através de um array
     * @param  Array  $data
     * 
     * @return Customer
     */
    public function fillEntity(Array $data)
    {
        return new Customer($data);
    }
}
