<?php

namespace Robertbaelde\Workshop\Kitchen;

use Robertbaelde\Workshop\Infra\MessageBusInterface;
use Robertbaelde\Workshop\Message;
use Robertbaelde\Workshop\MessageHandler;

class KitchenHandler implements MessageHandler
{
    public function __construct(protected MessageBusInterface $messageBus)
    {
    }

    public function handle(Message $message): void
    {
        if($message instanceof CookFood){
            $this->messageBus->publish(
                FoodCooked::fromCommand($message)
            );
        }
    }
}
