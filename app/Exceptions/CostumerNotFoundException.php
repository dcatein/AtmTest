<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CostumerNotFoundException extends Exception
{
    protected $message = "Customer_id não encontrado, verifique se este cliente está cadastrado"; 

    protected $code = Response::HTTP_NOT_FOUND;
}
