<?php

namespace App\Service;

use App\Models\Account;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CacheService;
use Illuminate\Support\Facades\DB;
use App\Exceptions\TransactionException;

class OperationService {

    const DEPOSIT_TRANSACTION = 1;
    const WITHDRAW_TRANSACTION = 2;

    /**
     * @var CacheService
     */
    protected $cacheService;

    /**
     * @var AccountService
     */
    protected $accountService;

    public function __construct(
        CacheService $cacheService,
        AccountService $accountService
    ) {
        $this->cacheService = $cacheService;
        $this->accountService = $accountService;
        $this->bankNotes = [100, 20, 50];
    }

    /**
     * Realiza um depósito na conta informada
     * 
     * @param Account $account
     * @param int $value
     * 
     * @return Account
     */
    public function deposit(Account $account, Int $value) :void
    {
        try {
            DB::beginTransaction();
            $redisKey = self::DEPOSIT_TRANSACTION . $account->id;

            $validateCache = $this->cacheService->validateCache($redisKey);

            if(!$validateCache){
                throw new TransactionException();
            }

            $account->balance = $this->depositValue($account, $value);

            $this->accountService->update($account, $account->id);

            $this->cacheService->clearCache($redisKey);

            DB::commit();
        }catch (TransactionException $e){
            DB::rollBack();
            throw $e;
        }catch (Exception $e){
            DB::rollBack();
            $this->cacheService->clearCache($redisKey);
            throw $e;
        }
    }

    /**
     * Realiza um saque na conta
     * 
     * @param Account $account
     * @param int $value
     * 
     * @return Array
     */
    public function withdraw(Account $account, Int $value) :Array
    {
        try {
            DB::beginTransaction();
            $redisKey = self::WITHDRAW_TRANSACTION . $account->id;
    
            $validateCache = $this->cacheService->validateCache($redisKey);

            if(!$validateCache){
                throw new TransactionException();
            }
    
            if($account->balance < $value){
                throw new Exception("Saldo insuficiente.", Response::HTTP_BAD_REQUEST);
            }
    
            $withdraw = $this->getBankNotes($value);
            
            if(!$withdraw){
                throw new Exception("Valor solicitado inválido. As notas disponíveis são: ". implode(",", $this->operationService->getAvaliableBankNotes()), Response::HTTP_BAD_REQUEST);
            }
    
            $account->balance = $this->withdrawValue($account, $value);
    
            $this->accountService->update($account, $account->id);

            $this->cacheService->clearCache($redisKey);

            DB::commit();
            return $withdraw;
        
        }catch (TransactionException $e){
            DB::rollBack();
            throw $e;
        }catch (Exception $e){
            DB::rollBack();
            $this->cacheService->clearCache($redisKey);
            throw $e;
        }
    }

    private function depositValue(Account $account, int $value)
    {
        return $account->balance + $value;
    }
  
    private function withdrawValue(Account $account, int $value)
    {
        return $account->balance - $value;
    }

    private function getBankNotes($value)
    {
        $bankNotes = $this->bankNotes;

        rsort($bankNotes);

        if(!$this->validateBankNotes($value, $bankNotes)){
            return false;
        }

        $withdraw = [];

        foreach ($bankNotes as $note) {

            while($value >= $note){
 
                $value = $value - $note;
 
                if(isset($withdraw[$note])){
                    $withdraw[$note] = $withdraw[$note] + 1;
                }else{
                    $withdraw[$note] = 1;
                }
            }

        }

        if($value > 0){
            return false;
        }

        return $withdraw;
    }

    private function validateBankNotes($value, $bankNotes)
    {
        $lowest = min($bankNotes);

        if($value < $lowest){
            return false;
        }

        return true;

    }

    public function getAvaliableBankNotes() :array
    {
        return $this->bankNotes;
    }
}