<?php

namespace Robertbaelde\Workshop\Waiter;

use Robertbaelde\Workshop\Event;
use Robertbaelde\Workshop\Items;
use Robertbaelde\Workshop\Message;
use Robertbaelde\Workshop\MessageHeader;
use Robertbaelde\Workshop\OrderId;
use Robertbaelde\Workshop\Table;

class OrderPlaced extends Message implements Event
{
    public function __construct(
        public readonly OrderId $orderId,
        public readonly Items $items,
        public readonly Table $table,
        public readonly bool $customerIsTrusted = true
    )
    {
        parent::__construct(MessageHeader::withCorrelationId($this->orderId->id), $this);
    }
}
