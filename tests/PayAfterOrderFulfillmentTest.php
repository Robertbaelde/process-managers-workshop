<?php

namespace Robertbaelde\Workshop\Tests;

use Robertbaelde\Workshop\CookingTimedOut;
use Robertbaelde\Workshop\DelayedMessage;
use Robertbaelde\Workshop\Items;
use Robertbaelde\Workshop\Kitchen\CookFood;
use Robertbaelde\Workshop\Kitchen\FoodCooked;
use Robertbaelde\Workshop\MessageHeader;
use Robertbaelde\Workshop\OrderId;
use Robertbaelde\Workshop\PayAfterOrderFulfillment;
use Robertbaelde\Workshop\ProcessManager;
use Robertbaelde\Workshop\Table;
use Robertbaelde\Workshop\Waiter\OrderPlaced;

class PayAfterOrderFulfillmentTest extends ProcessManagerTestCase
{

    /** @test */
    public function it_raises_the_cook_food_command_after_order_placed()
    {
        $this
            ->given()
            ->when(
                $orderPlaced = new OrderPlaced(new OrderId('1'), new Items(), new Table(1))
            )->expectMessageOnBus(
                new CookFood(new OrderId('1'), new Items(), new Table(1), MessageHeader::causedBy($orderPlaced->messageHeader)),
                new DelayedMessage(
                    3,
                    new CookingTimedOut(new OrderId('1'), new Items(), new Table(1),1, MessageHeader::causedBy($orderPlaced->messageHeader))
                ),
            );
    }

    /** @test */
    public function it_retries_cook_food()
    {
        $this
            ->given(
                $orderPlaced = new OrderPlaced(new OrderId('1'), new Items(), new Table(1))
            )
            ->when(
                new CookingTimedOut(new OrderId('1'), new Items(), new Table(1), 1, MessageHeader::causedBy($orderPlaced->messageHeader))
            )->expectMessageOnBus(
                new CookFood(new OrderId('1'), new Items(), new Table(1), MessageHeader::causedBy($orderPlaced->messageHeader)),
                new DelayedMessage(
                    3,
                    new CookingTimedOut(new OrderId('1'), new Items(), new Table(1),2, MessageHeader::causedBy($orderPlaced->messageHeader))
                ),
            );
    }

    /** @test */
    public function when_food_is_cooked_it_doesnt_retry_on_timeout()
    {
        $this
            ->given(
                $orderPlaced = new OrderPlaced(new OrderId('1'), new Items(), new Table(1)),
                FoodCooked::fromCommand(new CookFood(new OrderId('1'), new Items(), new Table(1), MessageHeader::causedBy($orderPlaced->messageHeader))),
            )
            ->when(
                new CookingTimedOut(new OrderId('1'), new Items(), new Table(1), 1, MessageHeader::causedBy($orderPlaced->messageHeader))
            )->expectMessageOnBus();
    }

    public function getProcessManager(): ProcessManager
    {
        return new PayAfterOrderFulfillment($this->getMessageBus());
    }
}
