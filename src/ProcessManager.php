<?php

namespace Robertbaelde\Workshop;

interface ProcessManager
{
    public function handle(Message $message): void;

    public function endsWhen(Message ...$messages): void;
}
