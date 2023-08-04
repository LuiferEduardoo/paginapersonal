<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;

class ApiKeyGenerator
{
    public static function generate(): string
    {
        $key = Uuid::uuid4()->toString();
        $hashedApiKey = bcrypt($key);
        return response()->json(['key' => $key,
        "hashedKey" => $hashedApiKey
    ], 200);
    }
}
