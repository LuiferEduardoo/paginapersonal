<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ValidateDate extends FormRequest
{

    public function rules(): array
    {
        switch ($this->route()->getActionMethod()) {
            case 'login': 
                return [
                    'email' => ['required','string', 'email', 'max:255'],
                    'password' => ['required', 'string'],
                ];
            case 'PostSkills':
                return [
                    'name' => ['required','string','max:255'],
                    'image' => ['nullable', 'image'],
                    'url' => ['nullable', 'url'],
                    'id_image' => ['nullable','string'],
                    'date' => ['required', 'string'],
                    'tags' => ['required', 'string'],
                ];
            case 'DeleteSkills':
                return [
                    'eliminate_image' => ['nullable','string']
                    ];
            case 'PutSkills':
                return [
                    'name' => ['required','string','max:255'],
                    'image' => ['nullable', 'image'],
                    'url' => ['nullable', 'url'],
                    'id_image' => ['nullable','string'],
                    'replace_image' => ['required','string'],
                    'date' => ['required', 'string'],
                    'tags' => ['required', 'string'],
                ];

            case 'PatchSkills':
                return [
                    'name' => ['nullable','string','max:255'],
                    'image' => ['nullable','image'],
                    'url' => ['nullable', 'url'],
                    'id_image' => ['nullable','string'],
                    'replace_image' => ['nullable','string'],
                    'date' => ['nullable','string'],
                    'tags' => ['nullable','string'],
                    'validate' => ['nullable','string']
                    ];
                // Definir reglas de validación para los otros métodos
            default:
                return [];
            }
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The data entered is invalid',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
