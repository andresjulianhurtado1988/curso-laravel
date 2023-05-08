<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'name'  =>  'required|max:191',
            'surname'  =>  'required|max:191',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
         
        ];
        return $rules;
    }

    public function message(){
        return [
            'name.required' => 'Nombre requerido.',
            'surname.required' => 'Apellido requerido.',
            'email.required' => 'Correo electrónico requerido.',
            'email.email' => 'Correo electrónico invalido.',
            'email.unique' => 'Correo electrónico no disponible.',

            'password.required' => 'Contraseña requerido.',
            'password.min' => 'Contraseña debe ser min. 6 caracteres.',
            'password.confirmed' => 'Contraseñas no coinciden.',

        ];
    }
}
