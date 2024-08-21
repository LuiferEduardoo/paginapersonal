<?php

namespace App\Utils;

class Time {
    public function readingTime($content){
        $numberWords = str_word_count(strip_tags($content));
        $readingTimeNoForm = ceil($numberWords / 200);
        $readingTimeHours = floor($readingTimeNoForm/60); 
        $readingTimeMinutes = $readingTimeNoForm % 60;
        return gmdate('H:i:s', mktime($readingTimeHours, $readingTimeMinutes, 0, 0, 0, 0));
    }
}