<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Service\CustomerService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CustomerRequest;


class CustomerController extends Controller
{

    /**
     * @var CustomerServices
     */
    protected CustomerService $customerService;

    public function __construct(
        CustomerService $customerService
    ){
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->customerService->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest $request
     * @return JsonResponse
     */
    public function store(CustomerRequest $request)
    {
        try {
            $customer = $this->customerService->fillEntity($request->all());
    
            $return = $this->customerService->create($customer);
    
            return new JsonResponse($return, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->customerService->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        try {
            $customer = $this->customerService->fillEntity($request->all());
    
            $this->customerService->update($customer, $id);
    
            return new JsonResponse($customer, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->customerService->delete($id);
    
            return new JsonResponse("Recurso deletado com sucesso", Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }
    }
}
