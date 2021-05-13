<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Service\CustomerService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CustomerRequest;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;
use App\Models\Customer;

class CustomerController extends Controller
{

    /**
     * @var CustomerServices
     */
    protected $customerService;

    public function __construct(
        CustomerService $customerService
    ){
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator;
     */
    public function index() :LengthAwarePaginator
    {
        return $this->customerService->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest $request
     * @return JsonResponse
     */
    public function store(CustomerRequest $request) :JsonResponse
    {
        try {
            $customer = $this->customerService->fillEntity($request->all());
    
            $return = $this->customerService->create($customer);
    
            return new JsonResponse($return, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Customer
     */
    public function show($id) :Customer
    {
        return $this->customerService->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(CustomerRequest $request, $id) :JsonResponse
    {
        try {
            $customer = $this->customerService->fillEntity($request->all());
    
            $this->customerService->update($customer, $id);
    
            return new JsonResponse($customer, Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id) :JsonResponse
    {
        try {
            $this->customerService->delete($id);
    
            return new JsonResponse("Recurso deletado com sucesso", Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }
}
