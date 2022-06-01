<?php

namespace App\UI\Http\User\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPersonalInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth()->user()->id == $this->input('id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => ['required', 'numeric', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            /*
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->input('id'))
            ]
            */
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'É necessário informar o ID do usuário.',
            'id.exists' => 'O usuário informado não existe em nosso banco de dados.',
            'name.required' => 'É necessário informar um nome para o usuário.',
            /*
            'email.required' => 'É necessário informar um e-mail para o usuário.',
            'email.email' => 'É necessário informar um e-mail válido para o usuário.',
            'email.unique' => 'Esse e-mail já está em uso por outro usuário.',
            */
        ];
    }
}
