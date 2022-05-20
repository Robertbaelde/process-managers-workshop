<?php

namespace Robertbaelde\Workshop\Infra;

use Robertbaelde\Workshop\Command;
use Robertbaelde\Workshop\Event;

interface MessageBusInterface
{
    public function send(Command $param): void;

    public function publish(Event $event): void;
}
