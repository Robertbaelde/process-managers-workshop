<?php

namespace Robertbaelde\Workshop;

use Robertbaelde\Workshop\Infra\MessageBusInterface;

class InMemoryDelayedMessageHandler implements MessageHandler
{
    private array $queuedMessages = [];

    public function __construct(protected MessageBusInterface $messageBus)
    {
    }

    public function handle(Message $message): void
    {
        if($message instanceof DelayedMessage){
            $this->queuedMessages[] = $message;
        }
    }

    public function tick(): void
    {
        $this->queuedMessages = array_filter($this->queuedMessages, function (DelayedMessage $message){
            if($message->shouldBeReleased()){
                $this->messageBus->publish($message->message);
                return false;
            }
            return true;
        });
    }
}
