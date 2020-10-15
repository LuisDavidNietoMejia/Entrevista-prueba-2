<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Library\ResponseJson;

class DocumentStoreRequest extends FormRequest
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
          'content' => 'required|regex:/^([a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃÃ‰ÃÃ“Ãš_-])+((\s*)+([a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃÃ‰ÃÃ“Ãš_-]*)*)+$/',
          'user_id' => 'required|exists:users,id',
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
            'content' => 'Contenido',
            'user_id' => 'Usuario'
        ];
    }

    public function messages()
    {
        return [
          'content.required' => 'El campo :attribute es requerido.',
          'content.regex' => 'El campo :attribute debe ser solo letras.',
          'user_id.required' => 'El campo :attribute es requerido.',
          'user_id.exists' => 'El campo :attribute no esta registrado en nuestro sistema.',
      ];
    }

    private function getSegmentFromEnd($position_from_end = 1)
    {
        $segments =$this->segments();
        return $segments[sizeof($segments) - $position_from_end];
    }

    protected function failedValidation(Validator $validator)
    {
        return ResponseJson::errorRequest($validator->errors());
    }
}
