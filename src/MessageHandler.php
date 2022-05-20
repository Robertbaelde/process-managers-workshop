<?php

namespace Robertbaelde\Workshop;

interface MessageHandler
{
    public function handle(Message $message): void;
}
