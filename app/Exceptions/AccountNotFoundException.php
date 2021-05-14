<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AccountNotFoundException extends Exception
{
    protected $message = "Conta não encontrada, verifique se este cliente está cadastrado";

    protected $code = Response::HTTP_NOT_FOUND;
}
