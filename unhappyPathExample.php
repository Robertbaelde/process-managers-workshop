<?php

use Robertbaelde\Workshop\Infra\InMemoryMessageBus;
use Robertbaelde\Workshop\InMemoryDelayedMessageHandler;
use Robertbaelde\Workshop\Items;
use Robertbaelde\Workshop\OrderId;
use Robertbaelde\Workshop\Table;
use Robertbaelde\Workshop\Waiter\OrderPlaced;

require __DIR__ . '/vendor/autoload.php';

$event = new OrderPlaced(
        OrderId::create(),
        new Items(),
        new Table(1)
);

$messageBus = new InMemoryMessageBus(
    \Robertbaelde\Workshop\InMemoryOrderProcessManagerHouse::class,
    \Robertbaelde\Workshop\Kitchen\UnreliableKitchenHandler::class,
    InMemoryDelayedMessageHandler::class,
);

$messageBus->publish($event);

while(true){
    $messageBus->tick();
}
