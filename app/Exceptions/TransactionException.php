<?php

namespace App\Exceptions;

use Exception;

class TransactionException extends Exception
{

    protected $message =  "Já existe uma transação deste tipo em andamento."; 

}
