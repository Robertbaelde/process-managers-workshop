<?php

namespace Robertbaelde\Workshop\Infra;

use Robertbaelde\Workshop\Command;
use Robertbaelde\Workshop\Event;
use Robertbaelde\Workshop\Message;
use Robertbaelde\Workshop\MessageHandler;

class InMemoryMessageBus implements MessageBusInterface
{
    /**
     * @var Message[]
     */
    private array $messages = [];

    /**
     * @var MessageHandler[]
     */
    private array $messageHandlers;

    public function __construct(string ...$messageHandlers)
    {
        foreach ($messageHandlers as $messageHandler){
            $this->messageHandlers[] = new $messageHandler($this);
        }
    }

    public function tick(): void
    {
        $message = array_shift($this->messages);
        if($message !== null){
            $this->handle($message);
        }

        foreach ($this->messageHandlers as $messageHandler){
            if(method_exists($messageHandler, 'tick')){
                $messageHandler->tick();
            }
        }
    }

    public function send(Command $command): void
    {
        print_r("received new command " . get_class($command) . "\n");
        $this->handleNewMessage($command);
    }

    public function publish(Event $event): void
    {
        print_r("received new event " . get_class($event) . "\n");
        $this->handleNewMessage($event);
    }

    public function handleNewMessage(Message $message): void
    {
        $this->messages[] = $message;
    }

    private function handle(Message $message): void
    {
        foreach ($this->messageHandlers as $messageHandler){
            $messageHandler->handle($message);
        }
    }

    public function flush(): void
    {
        $this->messages = [];
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
