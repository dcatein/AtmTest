<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Service\AccountService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\AccountRequest;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;
use App\Models\Account;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\CostumerNotFoundException;

class AccountController extends Controller
{

    /**
     * @var AccountService
     * 
     */
    private $accountService;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountRequest $request) :JsonResponse
    {
        try {
            $account = $this->accountService->fillEntity($request->all());
            
            $return = $this->accountService->create($account);

            return new JsonResponse($return, Response::HTTP_CREATED);
        } catch (CostumerNotFoundException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());

        } catch (Exception $e) {
            return new JsonResponse("Internal Error.", $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Account
     */
    public function show(int $id) :JsonResponse
    {
        try {
            $account = $this->accountService->find($id);

            if(!$account){
                throw new AccountNotFoundException();
            }

            return new JsonResponse($account, Response::HTTP_OK);

        } catch(AccountNotFoundException $e){
            return new JsonResponse($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AccountRequest $request, int $id) :JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) :JsonResponse
    {
        try {
            $account = $this->accountService->find($id);

            if(!$account){
                throw new AccountNotFoundException();
            }

            $this->accountService->delete($id);

            return new JsonResponse("Recurso deletado com sucesso", Response::HTTP_OK);
        } catch(AccountNotFoundException $e){
            return new JsonResponse($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

}
