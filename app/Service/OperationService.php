<?php

namespace App\Service;

use Illuminate\Support\Facades\Redis;
use App\Models\Account;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class OperationService {

    const DEPOSIT_TRANSACTION = 1;
    const WITHDRAW_TRANSACTION = 2;

    public function __construct() {
        $this->bankNotes = [100, 20, 50];
    }

    public function depositValue(Account $account, int $value)
    {
        return $account->balance + $value;
    }
  
    public function withdrawValue(Account $account, int $value)
    {
        return $account->balance - $value;
    }

    public function getBankNotes($value)
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
}