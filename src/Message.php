<?php

namespace Robertbaelde\Workshop;

abstract class Message
{
    public function __construct(
        public MessageHeader $messageHeader
    )
    {

    }

    public function withEmptyHeader(): self
    {
        $this->messageHeader = MessageHeader::empty();
        return $this;
    }
}
