<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModeloRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'marca_id' => 'required|exists:marcas,id', 
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3', 
            'imagem' => 'required|file',
            'numero_portas' => 'required|integer|digits_between:1,5', 
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean', 
            'abs' => 'required|boolean'
        ];
        
        if($this->method('PUT')){
            $rules['marca_id'] = 'sometimes|required|exists:marcas,id';
            $rules['nome'] = 'sometimes|required|unique:modelos,nome,'.$this->id.'|min:3';
            $rules['imagem'] = 'sometimes|file';
            $rules['numero_portas'] = 'sometimes|required|integer|digits_between:1,5';
            $rules['lugares'] = 'sometimes|required|integer|digits_between:1,20';
            $rules['air_bag'] = 'sometimes|required|boolean';
            $rules['abs'] = 'sometimes|required|boolean';
        }       

        return $rules;
    }
}
