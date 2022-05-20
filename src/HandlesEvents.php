<?php

namespace Robertbaelde\Workshop;

trait HandlesEvents
{
    public function handle(Message $message): void
    {
        $parts = explode('\\', get_class($message));
        $methodName = 'handle' . end($parts);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($message);
        }
    }
}
