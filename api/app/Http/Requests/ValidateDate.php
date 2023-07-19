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
            case 'updateInformationUser': 
                return [
                    'name' => ['nullable','string', 'max:255'],
                    'email' => ['nullable','string', 'email', 'max:255'],
                    'password' => ['nullable', 'string'],
                    'replace_image' => ['nullable','string'],
                    'image' => ['nullable', 'image'],
                    'id_image' => ['nullable','string'],
                ];
            case 'PostSkills':
                return [
                    'name' => ['required','string','max:255'],
                    'image' => ['nullable', 'image'],
                    'url' => ['nullable', 'url'],
                    'id_image' => ['nullable','string'],
                    'date' => ['required', 'string'],
                    'categories' => ['required', 'string'],
                    'subcategories' => ['required', 'string'],
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
                    'categories' => ['required', 'string'],
                    'subcategories' => ['required', 'string'],
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
                    'categories' => ['nullable', 'string'],
                    'subcategories' => ['nullable', 'string'],
                    'validate' => ['nullable','string']
                    ];
                // Definir reglas de validación para los otros métodos

                case 'postProyect':
                    return [
                        'name' => ['required','string','max:255'],
                        'brief_description' => ['required', 'string', 'max:5000'],
                        'url_repository' => ['required', 'url'],
                        'miniature' => ['nullable', 'image'],
                        'id_miniature' => ['nullable', 'string'],
                        'images' => ['nullable','image'],
                        'ids_images' => ['nullable','string'],
                        'categories' => ['required', 'string'],
                        'subcategories' => ['required', 'string'],
                        'technologies' => ['required', 'string'],
                        'tags' => ['required','string'],                        
                        ];
                case 'putProyect':
                    return [
                        'name' => ['required','string','max:255'],
                        'brief_description' => ['required', 'string', 'max:5000'],
                        'url_repository' => ['required', 'url'],
                        'miniature' => ['nullable', 'image'],
                        'id_miniature' => ['nullable', 'string'],
                        'images' => ['nullable','image'],
                        'ids_images' => ['nullable','string'],
                        'categories' => ['required', 'string'],
                        'subcategories' => ['required', 'string'],
                        'technologies' => ['required', 'string'],
                        'tags' => ['nullable','string'],
                        'replace_miniature' => ['nullable','string'],  
                        'replace_images' => ['nullable','string'],
                        ];
                case 'patchProyect':
                    return [
                        'name' => ['nullable','string','max:255'],
                        'brief_description' => ['nullable', 'string', 'max:5000'],
                        'url_repository' => ['nullable', 'url'],
                        'miniature' => ['nullable', 'image'],
                        'id_miniature' => ['nullable', 'string'],
                        'images' => ['nullable','image'],
                        'ids_images' => ['nullable','string'],
                        'categories' => ['nullable', 'string'],
                        'subcategories' => ['nullable', 'string'],
                        'technologies' => ['nullable', 'string'],
                        'tags' => ['nullable','string'],
                        'replace_miniature' => ['nullable','string'],  
                        'replace_images' => ['nullable','string'],
                        ];
            
                case 'postBlogPost':
                    return [
                        'title' => ['required','string','max:255'],
                        'content' => ['required', 'string'],
                        'authors' => ['required', 'string'],
                        'image_credits' => ['nullable', 'string'],
                        'images' => ['nullable','image'],
                        'id_image' => ['nullable','string'],
                        'categories' => ['required', 'string'],
                        'subcategories' => ['required', 'string'],
                        'tags' => ['nullable','string'],                        
                        ];

                case 'putBlogPost':
                    return [
                        'title' => ['required','string','max:255'],
                        'content' => ['required', 'string'],
                        'authors' => ['required', 'string'],
                        'image_credits' => ['nullable', 'string'],
                        'images' => ['nullable','image'],
                        'id_image' => ['nullable','string'],
                        'categories' => ['required', 'string'],
                        'subcategories' => ['required', 'string'],
                        'tags' => ['nullable','string'],
                        'replace_image' => ['required','string'],                        
                        ];


                case 'patchBlogPost':
                    return [
                        'title' => ['nullable','string','max:255'],
                        'content' => ['nullable', 'string'],
                        'authors' => ['nullable', 'string'],
                        'image_credits' => ['nullable', 'string'],
                        'images' => ['nullable','image'],
                        'id_image' => ['nullable','string'],
                        'categories' => ['nullable', 'string'],
                        'subcategories' => ['nullable', 'string'],
                        'tags' => ['nullable','string'],
                        'replace_image' => ['nullable','string'],                        
                        ];
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
