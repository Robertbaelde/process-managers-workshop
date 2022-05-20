<?php

namespace Robertbaelde\Workshop\Kitchen;

use Robertbaelde\Workshop\Infra\MessageBusInterface;
use Robertbaelde\Workshop\Message;

class UnreliableKitchenHandler
{
    public function __construct(protected MessageBusInterface $messageBus)
    {
    }

    public function handle(Message $message): void
    {
        if (rand(0, 5) > 1) {
            return;
        }

        if ($message instanceof CookFood) {
            $this->messageBus->publish(
                FoodCooked::fromCommand($message)
            );
        }
    }
}
