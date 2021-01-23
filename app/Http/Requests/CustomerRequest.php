<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class CustomerRequest extends FormRequest
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
            'name' => 'required|max:255',
            'date_of_birth' => 'required|date',
            'cpf' => 'required|digits:11|unique:App\Models\Customer,cpf'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo não pode ser nulo',
            'date_of_birth.required' => 'O campo não pode ser nulo',
            'cpf.required' => 'O campo não pode ser nulo',
            'name.max' => 'O campo não pode exceder 255 caracteres',
            'date_of_birth.date' => 'Precisa ser uma data',
            'cpf.size' => 'O campo deve possuir 11 caracteres',
            'cpf.unique' => 'CPF já cadastrado',
            'cpf.digits' => 'O campo deve conter 11 números'
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), Response::HTTP_BAD_REQUEST));
    }
}
