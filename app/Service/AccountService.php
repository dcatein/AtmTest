<?php

namespace App\Service;

use App\Repository\AccountRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redis;
use App\Models\Account;
use App\Service\OperationService;
use App\Service\CacheService;

class AccountService {
    
    /**
     * @var AccountRepository
     */
    protected AccountRepository $accountRepository;
    
    /**
     * @var OperationService
     */
    protected OperationService $operationService;
    
    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    const DEPOSIT_TRANSACTION = 1;
    const WITHDRAW_TRANSACTION = 2;

    public function __construct(
        AccountRepository $accountRepository,
        OperationService $operationService,
        CacheService $cacheService
    ){
        $this->accountRepository = $accountRepository;
        $this->operationService = $operationService;
        $this->cacheService = $cacheService;
        $this->bankNotes = [100, 20, 50];
    }

    /**
     * Registra a conta na tabela account
     * @param Account $entity
     * 
     * @return array
     */
    public function create(Account $entity)
    {
        $account = $this->findCustomerAccount($entity->customer_id, $entity->type);
        if($account){
           return [
               'data' => $account,
               'code' => Response::HTTP_FOUND
           ];
        }else{
            $account = $this->accountRepository->create($entity);
            return [
                'data' => $account,
                'code' => Response::HTTP_CREATED
            ];
        }
    }

    public function findAll()
    {
        return $this->accountRepository->findAll();
    }

    /**
     * Busca a account através do ID
     * @param int $id
     * 
     * @return Account
     */
    public function find(Int $id)
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
    public function findCustomerAccount(Int $customer_id, Int $account_type)
    {
        return $this->accountRepository->findCustomerAccount($customer_id, $account_type);
    }

    /**
     * Atualiza a conta na tabela accounts
     * @param Account $data
     * 
     * @return Account
     */
    public function update(Account $data, $id)
    {
        return $this->accountRepository->update($data, $id);
    }

    /**
     * Deleta a conta através do ID
     * @param  int  $id
     * 
     */
    public function delete(Int $id)
    {
        return $this->accountRepository->delete($id);
    }

    /**
     * Preenche a entidade através de um array
     * @param Array $data
     * 
     * @return Account
     */
    public function fillEntity(Array $data)
    {
        return new Account($data);
    }

    /**
     * Realiza um depósito na conta
     * 
     * @param Account $account
     * @param int $value
     * 
     * @return Account
     */
    public function deposit(Account $account, Int $value)
    {
        try {
            $redisKey = self::DEPOSIT_TRANSACTION . $account->id;

            $validateCache = $this->cacheService->validateCache($redisKey);

            if(!$validateCache){
                throw new Exception('Já existe uma transação deste tipo em andamento.', Response::HTTP_BAD_REQUEST);
            }

            $account->balance = $this->operationService->depositValue($account, $value);

            $this->update($account, $account->id);

        } finally {
            $this->cacheService->clearCache($redisKey);
        }
    }

    /**
     * Realiza um saque na conta
     * 
     * @param Account $account
     * @param int $value
     * 
     * @return Account
     */
    public function withdraw(Account $account, Int $value)
    {
        try {
            $redisKey = self::WITHDRAW_TRANSACTION . $account->id;
    
            $validateCache = $this->cacheService->validateCache($redisKey);

            if(!$validateCache){
                throw new Exception('Já existe uma transação deste tipo em andamento.', Response::HTTP_BAD_REQUEST);
            }
    
            if($account->balance < $value){
                throw new Exception("Saldo insuficiente.", Response::HTTP_BAD_REQUEST);
            }
    
            $withdraw = $this->operationService->getBankNotes($value);
            
            if(!$withdraw){
                throw new Exception("Valor solicitado inválido. As notas disponíveis são: ". implode(",", $this->bankNotes), Response::HTTP_BAD_REQUEST);
            }
    
            $account->balance = $this->operationService->withdrawValue($account, $value);
    
            $this->update($account, $account->id);
            
            return $withdraw;

        } finally {
            $this->cacheService->clearCache($redisKey);
        }
    }



}