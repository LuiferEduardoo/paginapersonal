<?php

namespace App\Utils;

class Time {
    public function readingTime($content) {
        $numberWords = str_word_count(strip_tags($content));
        $readingTimeMinutes = floor($numberWords / 200); // Minutos enteros
        $readingTimeSeconds = round(($numberWords % 200) / (200 / 60)); // Segundos fraccionarios
        return gmdate('H:i:s', mktime(0, $readingTimeMinutes, $readingTimeSeconds, 0, 0, 0));
    }
}