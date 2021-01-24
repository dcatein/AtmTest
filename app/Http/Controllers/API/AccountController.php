<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service\AccountService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\AccountTransactionRequest;
use App\Http\Requests\AccountRequest;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;
use App\Models\Account;
use App\Exceptions\TransactionException;

class AccountController extends Controller
{

    /**
     * @var AccountService
     */
    protected AccountService $accountService;

    public function __construct(
        AccountService $accountService
    ){
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index() :LengthAwarePaginator
    {
        return $this->accountService->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AccountRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request) :JsonResponse
    {
        try {
            $account = $this->accountService->fillEntity($request->all());

            $return = $this->accountService->create($account);

            return new JsonResponse($return, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Account
     */
    public function show($id) :Account
    {
        return $this->accountService->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRequest $request, $id) :JsonResponse
    {
        try {
            $account = $this->accountService->fillEntity($request->all());
    
            $this->accountService->update($account, $id);
    
            return new JsonResponse($account, Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) :JsonResponse
    {
        $this->accountService->delete($id);

        return new JsonResponse("Recurso deletado com sucesso", Response::HTTP_OK);
    }

     /**
     *
     * @param  \App\Http\Requests\AccountTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(AccountTransactionRequest $request) :JsonResponse
    {
        try {

            $account = $this->accountService->find($request->getAccountId());
            
            if(!$account){
                throw new ModelNotFoundException("Conta não encontrada", Response::HTTP_NOT_FOUND);
            }

            $this->accountService->deposit($account, $request->getValue());

            return new JsonResponse("Operação realizada com sucesso", Response::HTTP_OK);

        } catch(ModelNotFoundException $e){
            return new JsonResponse($e->getMessage(), $e->getCode());
        } catch(TransactionException $e){
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
        
    }

     /**
     *
     * @param  \App\Http\Requests\AccountTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(AccountTransactionRequest $request) :JsonResponse
    {
        try {

            $account = $this->accountService->find($request->getAccountId());

            if(!$account){
                throw new ModelNotFoundException("Conta não encontrada", Response::HTTP_NOT_FOUND);
            }

            $response = $this->accountService->withdraw($account, $request->getValue());

            return new JsonResponse($response, Response::HTTP_OK);
            
        } catch(ModelNotFoundException $e){
            return new JsonResponse($e->getMessage(), $e->getCode());
        }catch(TransactionException $e){
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

}
