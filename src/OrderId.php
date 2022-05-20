<?php

namespace Robertbaelde\Workshop;

use Robertbaelde\Workshop\Infra\UuidGenerator;

class OrderId
{
    public function __construct(
        public readonly string $id
    )
    {
    }

    public static function create(): self
    {
        return new self(UuidGenerator::generateId());
    }
}
