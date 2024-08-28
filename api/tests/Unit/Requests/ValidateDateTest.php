<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\ValidateDate;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidateDateTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Mockeamos la ruta para que devuelva el método de la ruta que queremos probar
        $this->routeMock = \Mockery::mock(Route::class);
    }

    protected function createValidateDateRequest($method)
    {
        $this->routeMock->shouldReceive('getActionMethod')->andReturn($method);

        $request = new ValidateDate();
        $request->setRouteResolver(function () {
            return $this->routeMock;
        });

        return $request;
    }

    public function testLoginRules()
    {
        $request = $this->createValidateDateRequest('login');
        $rules = $request->rules();

        $expectedRules = [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testUpdateInformationUserRules()
    {
        $request = $this->createValidateDateRequest('updateInformationUser');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'password' => ['nullable', 'string'],
            'replace_image' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'id_image' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    // Pruebas similares se pueden agregar para los otros métodos
    public function testCreateSkillsRules()
    {
        $request = $this->createValidateDateRequest('createSkills');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image'],
            'id_image' => ['nullable', 'string'],
            'date' => ['required', 'string'],
            'categories' => ['required', 'string'],
            'subcategories' => ['required', 'string'],
            'tags' => ['required', 'string'],
            'replace_image' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testPutSkillsRules()
    {
        $request = $this->createValidateDateRequest('putSkills');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image'],
            'id_image' => ['nullable', 'string'],
            'date' => ['required', 'string'],
            'categories' => ['required', 'string'],
            'subcategories' => ['required', 'string'],
            'tags' => ['required', 'string'],
            'replace_image' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testDeleteSkillsRules()
    {
        $request = $this->createValidateDateRequest('deleteSkills');
        $rules = $request->rules();

        $expectedRules = [
            'eliminate_image' => ['nullable', 'boolean'],
            'eliminate_images' => ['nullable', 'boolean'],
            'eliminate_miniature' => ['nullable', 'boolean'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testPatchSkillsRules()
    {
        $request = $this->createValidateDateRequest('patchSkills');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['nullable', 'string', 'max:255'],
            'brief_description' => ['nullable', 'string', 'max:5000'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'authors' => ['nullable', 'string'],
            'image_credits' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'id_image' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string'],
            'date' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'categories' => ['nullable', 'string'],
            'subcategories' => ['nullable', 'string'],
            'validate' => ['nullable', 'string'],
            'replace_image' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testCreateProjectRules()
    {
        $request = $this->createValidateDateRequest('createProject');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['required', 'string', 'max:255'],
            'brief_description' => ['required', 'string', 'max:5000'],
            'description' => ['required', 'string'],
            'project_link' => ['nullable', 'url'],
            'url_repositories' => ['required', 'string'],
            'categories_repositories' => ['required', 'string'],
            'miniature' => ['nullable', 'image'],
            'id_miniature' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'ids_images' => ['nullable', 'string'],
            'categories' => ['required', 'string'],
            'subcategories' => ['required', 'string'],
            'technologies' => ['required', 'string'],
            'tags' => ['required', 'string'],
            'replace_miniature' => ['nullable', 'string'],
            'replace_images' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testPatchProjectRules()
    {
        $request = $this->createValidateDateRequest('patchProject');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['nullable', 'string', 'max:255'],
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
            'images' => ['nullable', 'array'],
            'ids_images' => ['nullable', 'string'],
            'categories' => ['nullable', 'string'],
            'subcategories' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'replace_miniature' => ['nullable', 'string'],
            'replace_images' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testCreateBlogPostRules()
    {
        $request = $this->createValidateDateRequest('createBlogPost');
        $rules = $request->rules();

        $expectedRules = [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'authors' => ['nullable', 'string'],
            'image_credits' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'id_image' => ['nullable', 'string'],
            'categories' => ['required', 'string'],
            'subcategories' => ['required', 'string'],
            'tags' => ['required', 'string'],
            'replace_image' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    public function testPatchBlogPostRules()
    {
        $request = $this->createValidateDateRequest('patchBlogPost');
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['nullable', 'string', 'max:255'],
            'brief_description' => ['nullable', 'string', 'max:5000'],
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'authors' => ['nullable', 'string'],
            'image_credits' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
            'id_image' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string'],
            'date' => ['nullable', 'string'],
            'tags' => ['nullable', 'string'],
            'categories' => ['nullable', 'string'],
            'subcategories' => ['nullable', 'string'],
            'validate' => ['nullable', 'string'],
            'replace_image' => ['nullable', 'string'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}