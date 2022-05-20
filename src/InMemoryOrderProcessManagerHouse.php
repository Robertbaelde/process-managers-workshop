<?php

namespace Robertbaelde\Workshop;

use Robertbaelde\Workshop\Infra\MessageBusInterface;
use Robertbaelde\Workshop\Waiter\OrderPlaced;

class InMemoryOrderProcessManagerHouse implements MessageHandler
{
    /**
     * @var ProcessManager[]
     */
    private array $processes = [];

    public function __construct(
        protected MessageBusInterface $messageBus
    )
    {
    }

    public function handle(Message $message): void
    {
        $this->startNewProcessManagersForMessage($message);
        $this->handleActiveProcessManagerForMessage($message);

    }

    private function handleActiveProcessManagerForMessage(Message $message): void
    {
        if(array_key_exists($message->messageHeader->correlationId, $this->processes)){
            $this->processes[$message->messageHeader->correlationId]->handle($message);
        }
    }

    private function startNewProcessManagersForMessage(Message $message)
    {
        if($message instanceof OrderPlaced){
            if($message->customerIsTrusted){
                $this->processes[$message->messageHeader->correlationId] = new PayAfterOrderFulfillment($this->messageBus);
            }
        }
    }
}
