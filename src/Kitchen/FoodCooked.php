<?php

namespace Robertbaelde\Workshop\Kitchen;

use Robertbaelde\Workshop\Items;
use Robertbaelde\Workshop\Message;
use Robertbaelde\Workshop\MessageHeader;
use Robertbaelde\Workshop\OrderId;
use Robertbaelde\Workshop\Table;

class FoodCooked extends Message implements \Robertbaelde\Workshop\Event
{
    public function __construct(
        public readonly OrderId $orderId,
        public readonly Items $items,
        public readonly Table $table,
        ?MessageHeader $messageHeader = null,
    )
    {
        parent::__construct($messageHeader === null ? MessageHeader::withCorrelationId($this->orderId->id) : $messageHeader);
    }

    public static function fromCommand(CookFood $cookFood): self
    {
        return new self($cookFood->orderId, $cookFood->items, $cookFood->table, MessageHeader::causedBy($cookFood->messageHeader));
    }
}
