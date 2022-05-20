<?php

namespace Robertbaelde\Workshop;

interface ProcessManager
{
    public function handle(Message $message): void;

    public static function shouldStart(Message $message): bool;

    public function endsWhen(Message ...$messages): void;
}
