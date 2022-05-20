<?php

namespace Robertbaelde\Workshop\Infra;

class UuidGenerator
{
    public static function generateId(): string
    {
        return (string) rand(10000, 99999);
    }
}
