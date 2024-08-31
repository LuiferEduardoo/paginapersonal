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
            case 'createSkills': 
                return [
                    'name' => ['required','string','max:255'],
                    'image' => ['nullable', 'image'],
                    'id_image' => ['nullable','string'],
                    'date' => ['required', 'string'],
                    'categories' => ['required', 'string'],
                    'subcategories' => ['required', 'string'],
                    'tags' => ['required', 'string'],
                    'replace_image' => ['nullable','string'],
                ];
            case 'updateSkills': 
                return [
                    'name' => ['nullable','string','max:255'],
                    'image' => ['nullable','image'],
                    'id_image' => ['nullable','string'],
                    'date' => ['nullable','string'],
                    'tags' => ['nullable','string'],
                    'categories' => ['nullable', 'string'],
                    'subcategories' => ['nullable', 'string'],
                    'replace_image' => ['nullable','string'],
                ];

            case 'deleteSkills':
            case 'deleteProject':
            case 'deleteBlogPost':
                return [
                    'eliminate_image' => ['nullable','boolean'],
                    'eliminate_images' => ['nullable','boolean'],
                    'eliminate_miniature' => ['nullable','boolean']
                    ];
            
            case 'updateBlogPost':
                return [
                    'brief_description' => ['nullable', 'string', 'max:5000'],
                    'title' => ['nullable','string','max:255'],
                    'content' => ['nullable', 'string'],
                    'authors' => ['nullable', 'string'],
                    'image_credits' => ['nullable', 'string'],
                    'image' => ['nullable','image'],
                    'id_image' => ['nullable','string'],
                    'technologies' => ['nullable', 'string'],
                    'tags' => ['nullable','string'],
                    'categories' => ['nullable', 'string'],
                    'subcategories' => ['nullable', 'string'],
                    'validate' => ['nullable','string'],
                    'replace_image' => ['nullable','string'],
                ];

                case 'createProject':
                    return [
                        'name' => ['required','string','max:255'],
                        'brief_description' => ['required', 'string', 'max:5000'],
                        'description' => ['required', 'string'],
                        'project_link' => ['nullable', 'url'],
                        'url_repositories' => ['required', 'string'],
                        'categories_repositories' => ['required', 'string'],
                        'miniature' => ['nullable', 'image'],
                        'id_miniature' => ['nullable', 'string'],
                        'images' => ['nullable','array'],
                        'ids_images' => ['nullable','string'],
                        'categories' => ['required', 'string'],
                        'subcategories' => ['required', 'string'],
                        'technologies' => ['required', 'string'],
                        'tags' => ['required','string'],
                        'replace_miniature' => ['nullable','string'],  
                        'replace_images' => ['nullable','string'],                        
                        ];
            
                case 'updateProject':
                    return [
                        'name' => ['nullable','string','max:255'],
                        'brief_description' => ['nullable', 'string', 'max:5000'],
                        'description' => ['nullable', 'string'],
                        'project_link' => ['nullable', 'url'],
                        'url_repositories' => ['nullable', 'string'],
                        'ids_update_repositories' => ['nullable', 'string'],
                        'ids_eliminate_repositories' => ['nullable', 'string'],
                        'categories_repositories' => ['nullable', 'string'],
                        'categories_repositories_update' => ['nullable', 'string'],
                        'miniature' => ['nullable', 'image'],
                        'id_miniature' => ['nullable', 'string'],
                        'images' => ['nullable','array'],
                        'ids_images' => ['nullable','string'],
                        'categories' => ['nullable', 'string'],
                        'subcategories' => ['nullable', 'string'],
                        'technologies' => ['nullable', 'string'],
                        'tags' => ['nullable','string'],
                        'replace_miniature' => ['nullable','string'],  
                        'replace_images' => ['nullable','string'],     
                    ];
                
                case 'createBlogPost':
                    return [
                        'title' => ['required','string','max:255'],
                        'content' => ['required', 'string'],
                        'authors' => ['nullable', 'string'],
                        'image_credits' => ['nullable', 'string'],
                        'image' => ['nullable','image'],
                        'id_image' => ['nullable','string'],
                        'categories' => ['required', 'string'],
                        'subcategories' => ['required', 'string'],
                        'tags' => ['required','string'],
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