<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    abstract public function rules(): array;

    public function authorize(): bool
    {
        return true;
    }

    protected function baseMessages(): array
    {
        return [
            'required' => 'Le champ :attribute est requis.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'unique' => 'La valeur du champ :attribute existe déjà.',
            'min' => 'Le champ :attribute doit contenir au moins :min caractères.',
            'max' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
        ];
    }

    public function messages(): array
    {
        return array_merge($this->baseMessages(), $this->customMessages());
    }

    protected function customMessages(): array
    {
        return [];
    }
}
