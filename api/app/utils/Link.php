<?php

namespace App\Utils;

class Link 
{
    public function generate($title, $object)
    {
        // Eliminar caracteres especiales y conservar tildes
        $link = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $title));
        $link = preg_replace('/[^a-z0-9\-]/', '', str_replace(' ', '-', $link));
    
        $baseLink = $link;
        $suffix = 1;
        while ($object::where('link', $link)->exists()) {
            $link = "$baseLink-$suffix";
            $suffix++;
        }
        return $link;
    }
}