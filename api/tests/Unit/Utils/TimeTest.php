<?php

namespace Tests\Unit\Utils;

use App\utils\Time;
use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    /** @var Time */
    protected $time;

    protected function setUp(): void
    {
        parent::setUp();
        $this->time = new Time();
    }

    public function testReadingTimeCalculatesCorrectly()
    {
        $content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque in urna ligula.";
        $result = $this->time->readingTime($content);
        $expected = '00:00:04';

        $this->assertEquals($expected, $result);
    }

    public function testReadingTimeWithDifferentContent()
    {
        $content = str_repeat("word ", 400);
        $result = $this->time->readingTime($content);
        $expected = '00:02:00';

        $this->assertEquals($expected, $result);
    }

    public function testReadingTimeWithEmptyContent()
    {
        $content = "";
        $result = $this->time->readingTime($content);
        $expected = '00:00:00';

        $this->assertEquals($expected, $result);
    }
}