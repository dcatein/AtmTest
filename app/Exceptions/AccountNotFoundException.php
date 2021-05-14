<?php

namespace App\Exceptions;

use Exception;

class AccountNotFoundException extends Exception
{

    protected $message =  "Conta não encontrada, verifique se este cliente está cadastrado"; 

}
