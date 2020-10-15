<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Library\ResponseJson;

class UserStoreRequest extends FormRequest
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
          'name' => 'required|regex:/^([a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃÃ‰ÃÃ“Ãš_-])+((\s*)+([a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃÃ‰ÃÃ“Ãš_-]*)*)+$/',
          'email' => 'required|email|unique:users,email'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'email' => 'Correo Electrónico'
        ];
    }

    public function messages()
  {
      return [
          'name.required' => 'El campo :attribute es requerido.',
          'name.regex' => 'El campo :attribute debe ser solo letras.',
          'email.required' => 'El campo :attribute es requerido.',
          'email.email' => 'El campo :attribute debe ser un correo valido.',
          'email.unique' => 'El campo :attribute ya esta registrado en nuestro sistema.',
      ];
  }

    protected function failedValidation(Validator $validator)
    {
        return ResponseJson::errorRequest($validator->errors());
    }
}
