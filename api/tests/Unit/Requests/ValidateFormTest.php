<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\ValidateForm;
use Tests\TestCase;

class ValidateFormTest extends TestCase
{
    public function testRules()
    {
        $request = new ValidateForm();
        $rules = $request->rules();

        $expectedRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'subject' => ['required', 'string', 'max:255'],
        ];

        $this->assertEquals($expectedRules, $rules);
    }
}
