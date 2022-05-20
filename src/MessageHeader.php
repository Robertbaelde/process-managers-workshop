<?php

namespace Robertbaelde\Workshop;

use Robertbaelde\Workshop\Infra\UuidGenerator;

class MessageHeader
{
    public function __construct(
        public readonly string $id,
        public readonly string $correlationId,
        public readonly ?string $causationId = null
    )
    {

    }

    public static function withCorrelationId(string $correlationId): self
    {
        return new self(UuidGenerator::generateId(), $correlationId);
    }

    public static function causedBy(MessageHeader $causedBy): self
    {
        return new self(
            UuidGenerator::generateId(),
            $causedBy->correlationId,
            $causedBy->causationId
        );
    }

    public static function empty(): self
    {
        return new self(0, 0);
    }
}
