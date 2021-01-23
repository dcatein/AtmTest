<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TransactionException extends Exception
{

    protected $message =  "Já existe uma transação deste tipo em andamento."; 

}
