<?php

namespace App\Service;

use App\Repository\AccountRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Account;
use App\Service\CustomerService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CostumerNotFoundException;

class AccountService {
    
    /**
     * @var AccountRepository
     */
    protected $accountRepository;
    
    /**
     * @var CustomerService
     */
    protected $customerService;

    public function __construct(
        AccountRepository $accountRepository,
        CustomerService $customerService
    ){
        $this->accountRepository = $accountRepository;
        $this->customerService = $customerService;
    }

    /**
     * Registra a conta na tabela account
     * @param Account $entity
     * 
     * @return array
     */
    public function create(Account $entity)
    {
        try {
            DB::beginTransaction();

            $customer = $this->customerService->find($entity->customer_id);

            if(!$customer){
                throw new CostumerNotFoundException("Customer_id não encontrado, verifique se este cliente está cadastrado.", Response::HTTP_NOT_FOUND);
            }

            DB::commit();

            return $this->accountRepository->create($entity);
        } catch (CostumerNotFoundException $e) {
            DB::rollBack();
            throw $e;
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
        return $this->accountRepository->findAll();
    }

    /**
     * Busca a account através do ID
     * @param int $id
     * 
     * @return Account
     */
    public function find(Int $id) :?Account
    {
        return $this->accountRepository->find($id);
    }

    /**
     * Busca a account através do customer_id e type
     * @param int $customer_id
     * @param int $account_type
     * 
     * @return Account
     */
    public function findCustomerAccount(Int $customer_id, Int $account_type) :Account
    {
        return $this->accountRepository->findCustomerAccount($customer_id, $account_type);
    }

    /**
     * Atualiza a conta na tabela accounts
     * @param Account $data
     * 
     * @return Account
     */
    public function update(Account $data, $id) :Account
    {
        try {
            DB::beginTransaction();
            $account = $this->accountRepository->find($id);

            if(!$account){
                throw new AccountNotFoundException("Conta não encontrada, verifique se este cliente está cadastrado.", Response::HTTP_NOT_FOUND);
            }

            $account = $this->accountRepository->update($data, $id);
            DB::commit();

            return $account;
        } catch (AccountNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Deleta a conta através do ID
     * @param  int  $id
     * 
     */
    public function delete(Int $id) :void
    {
        try {
            DB::beginTransaction();
            $this->accountRepository->delete($id);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Preenche a entidade através de um array
     * @param Array $data
     * 
     * @return Account
     */
    public function fillEntity(Array $data) :Account
    {
        return new Account($data);
    }

}