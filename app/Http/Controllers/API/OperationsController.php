<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Service\OperationService;
use App\Service\AccountService;
use App\Http\Controllers\Controller;
use Exception;
use App\Http\Requests\AccountTransactionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\TransactionException;

class OperationsController extends Controller
{
    /**
     * @var OperationService
     */
    protected OperationService $operationService;

    /**
     * @var AccountService
     */
    protected AccountService $accountService;

    public function __construct(
        OperationService $operationService,
        AccountService $accountService
    ){
        $this->operationService = $operationService;
        $this->accountService = $accountService;
    }

    /**
     *
     * @param  \App\Http\Requests\AccountTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deposit(AccountTransactionRequest $request) :JsonResponse
    {
        try {
            $account = $this->accountService->find($request->getAccountId());

            if(!$account){
                throw new ModelNotFoundException("Conta não encontrada", JsonResponse::HTTP_NOT_FOUND);
            }

            $this->operationService->deposit($account, $request->getValue());

            return new JsonResponse("Operação realizada com sucesso", JsonResponse::HTTP_OK);

        } catch(ModelNotFoundException $e){
            return new JsonResponse($e->getMessage(), $e->getCode());
        } catch(TransactionException $e){
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     *
     * @param  \App\Http\Requests\AccountTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(AccountTransactionRequest $request) :JsonResponse
    {
        try {

            $account = $this->accountService->find($request->getAccountId());

            if(!$account){
                throw new ModelNotFoundException("Conta não encontrada", JsonResponse::HTTP_NOT_FOUND);
            }

            $response = $this->operationService->withdraw($account, $request->getValue());

            return new JsonResponse($response, JsonResponse::HTTP_OK);
            
        } catch(ModelNotFoundException $e){
            return new JsonResponse($e->getMessage(), $e->getCode());
        }catch(TransactionException $e){
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }
}
