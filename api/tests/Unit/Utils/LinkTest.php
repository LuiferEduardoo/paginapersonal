<?php

namespace Tests\Unit\Utils;

use Mockery;
use App\utils\Link;
use PHPUnit\Framework\TestCase;
use App\Models\BlogPost;

class LinkTest extends TestCase
{
    protected $objectMock;

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testGenerateLinkWithoutSpecialCharacters()
    {
        $title = "Hello World";
        
        $modelMock = Mockery::mock('overload:App\Models\Post');
        $modelMock->shouldReceive('where')
            ->andReturnSelf();
        $modelMock->shouldReceive('exists')
            ->andReturn(false);

        $link = new Link();
        $generatedLink = $link->generate($title, $modelMock);

        $this->assertEquals('hello-world', $generatedLink);
    }

    public function testGenerateLinkWithSpecialCharacters()
    {
        $title = "¡Hola Mundo!";
        
        $modelMock = Mockery::mock('overload:App\Models\Post');
        $modelMock->shouldReceive('where')
            ->andReturnSelf();
        $modelMock->shouldReceive('exists')
            ->andReturn(false);

        $link = new Link();
        $generatedLink = $link->generate($title, $modelMock);

        $this->assertEquals('hola-mundo', $generatedLink);
    }

    public function testGenerateLinkWithTilde()
    {
        $title = "Héllo Wórld";
        
        $modelMock = Mockery::mock('overload:App\Models\Post');
        $modelMock->shouldReceive('where')
            ->andReturnSelf();
        $modelMock->shouldReceive('exists')
            ->andReturn(false);

        $link = new Link();
        $generatedLink = $link->generate($title, $modelMock);

        $this->assertEquals('hello-world', $generatedLink);
    }

    public function testGenerateLinkWithSuffix()
    {
        $title = "Hello World";
        
        $modelMock = Mockery::mock('overload:App\Models\Post');
        $modelMock->shouldReceive('where')
            ->andReturnSelf();
        $modelMock->shouldReceive('exists')
            ->andReturn(true, false);

        $link = new Link();
        $generatedLink = $link->generate($title, $modelMock);

        $this->assertEquals('hello-world-1', $generatedLink);
    }
}
