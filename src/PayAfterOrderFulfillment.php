<?php

namespace Robertbaelde\Workshop;

use Robertbaelde\Workshop\Infra\MessageBusInterface;
use Robertbaelde\Workshop\Kitchen\CookFood;
use Robertbaelde\Workshop\Kitchen\FoodCooked;
use Robertbaelde\Workshop\Waiter\OrderPlaced;

class PayAfterOrderFulfillment implements ProcessManager
{
    use HandlesEvents;
    private bool $foodCooked = false;

    public function __construct(protected MessageBusInterface $messageBus)
    {

    }

    protected function handleOrderPlaced(OrderPlaced $orderPlaced)
    {
        $this->messageBus->send(new CookFood($orderPlaced->orderId, $orderPlaced->items, $orderPlaced->table, MessageHeader::causedBy($orderPlaced->messageHeader)));
        $this->messageBus->publish(new DelayedMessage(
            3,
            new CookingTimedOut($orderPlaced->orderId, $orderPlaced->items, $orderPlaced->table,1, MessageHeader::causedBy($orderPlaced->messageHeader))
            )
        );
    }

    protected function handleCookingTimedOut(CookingTimedOut $cookingTimedOut)
    {
        if($this->foodCooked){
            return;
        }
        $this->messageBus->send(new CookFood(
            $cookingTimedOut->orderId,
            $cookingTimedOut->items,
            $cookingTimedOut->table,
            MessageHeader::causedBy($cookingTimedOut->messageHeader)));

        $this->messageBus->publish(new DelayedMessage(
                3,
                new CookingTimedOut(
                    $cookingTimedOut->orderId,
                    $cookingTimedOut->items,
                    $cookingTimedOut->table,
                    $cookingTimedOut->tries + 1,
                    MessageHeader::causedBy($cookingTimedOut->messageHeader)
                )
            )
        );
    }

    public function handleFoodCooked(FoodCooked $foodCooked): void
    {
        $this->foodCooked = true;
    }

    public function endsWhen(Message ...$messages): void
    {

    }
}
