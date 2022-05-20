<?php

namespace Robertbaelde\Workshop;

class CookingTimedOut extends Message implements Event
{

    public function __construct(
        public readonly OrderId $orderId,
        public readonly Items $items,
        public readonly Table $table,
        public readonly int $tries,
        MessageHeader $messageHeader
    )
    {
        parent::__construct($messageHeader);
    }
}
