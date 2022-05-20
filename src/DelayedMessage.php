<?php

namespace Robertbaelde\Workshop;

class DelayedMessage extends Message implements Event
{
    private \DateTimeImmutable $releaseAt;

    public function __construct(
        public readonly int $delayInSeconds,
        public readonly Event | Command | Message $message
    )
    {
        $this->releaseAt = (new \DateTimeImmutable())->add(new \DateInterval("PT{$this->delayInSeconds}S"));
        parent::__construct(MessageHeader::empty());
    }

    public function shouldBeReleased(): bool
    {
        return $this->releaseAt <= new \DateTimeImmutable();
    }

    public function withEmptyHeader(): self
    {
        unset($this->releaseAt);
        $this->message->withEmptyHeader();
        return $this;
    }
}
