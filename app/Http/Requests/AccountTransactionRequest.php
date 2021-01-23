<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class AccountTransactionRequest extends FormRequest
{

    private $accountFieldName = 'account_id';
    private $valueFieldName = 'value';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'required|integer|gte:1',
            'account_id' => 'required',
        ]; 
    }

    public function messages()
    {
        return [
            'value.required' => 'O campo nao pode ser nulo',
            'value.integer' => 'Valor inválido. Não é permitido operações com centavos.',
            'account_id.required' => 'O campo não pode ser nulo',
            'value.gte' => 'O valor precisa ser igual ou maior que um.',
        ];
    }

    public function getAccountId()
    {
        return $this->{$this->accountFieldName};
    }
    
    public function getValue()
    {
        return $this->{$this->valueFieldName};
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
