<?php

namespace App\Exceptions;

use Exception;

class CostumerNotFoundException extends Exception
{

    protected $message =  "Customer_id não encontrado, verifique se este cliente está cadastrado"; 

}
