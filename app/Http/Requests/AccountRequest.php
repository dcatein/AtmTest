<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
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
            'customer_id' => ['required'],
            'type' => ['required', Rule::in([0,1])],
            'balance' => 'integer|filled|gte:0'
        ];
    }

    public function messages()
    {
        return [
            'customer_id.required' => 'O campo nao pode ser nulo.',
            'type.required' => 'O campo nao pode ser nulo.',
            'balance.integer' => 'Valor inválido. Não é permitido operações com centavos.',
            'balance.filled' => 'Valor inválido.',
            'balance.gte' => 'O valor precisa ser igual ou maior que zero.',
            'type.in' => 'O tipo da conta só pode ser: 0 - Poupança; 1 - Corrente',
        ];
    }

    protected function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
