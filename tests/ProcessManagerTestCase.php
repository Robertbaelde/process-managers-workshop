<?php

namespace Robertbaelde\Workshop\Tests;

use PHPUnit\Framework\TestCase;
use Robertbaelde\Workshop\Infra\InMemoryMessageBus;
use Robertbaelde\Workshop\Infra\MessageBusInterface;
use Robertbaelde\Workshop\Message;
use Robertbaelde\Workshop\ProcessManager;

abstract class ProcessManagerTestCase extends TestCase
{
    private InMemoryMessageBus $messageBus;
    private ProcessManager $processManager;

    /**
     * @var Message[]
     */
    private array $expectedMessages = [];

    public function setUp(): void
    {
        $this->messageBus = new InMemoryMessageBus();
        $this->processManager = $this->getProcessManager();
    }

    public function tearDown(): void
    {
        $this->assertEquals(
            $this->withoutHeaders(...$this->expectedMessages),
            $this->withoutHeaders(...$this->messageBus->getMessages()));
        parent::tearDown();
    }

    public abstract function getProcessManager(): ProcessManager;

    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    public function given(Message ...$messages): self
    {
        foreach ($messages as $message){
            $this->processManager->handle($message);
        }
        $this->messageBus->flush();
        return $this;
    }

    public function when(Message $message): self
    {
        $this->processManager->handle($message);
        return $this;
    }

    public function expectMessageOnBus(Message ...$messages): self
    {
        $this->expectedMessages += $messages;
        return $this;
    }

    private function withoutHeaders(Message ...$messages): array
    {
        return array_map(function (Message $message){
           return $message->withEmptyHeader();
        }, $messages);
    }
}
