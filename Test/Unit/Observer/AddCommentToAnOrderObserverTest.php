<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Test\Unit\Observer;

use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Sales\Model\Order;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\TestCase;
use YellowCard\OrderCommentProcessing\Observer\AddCommentToAnOrderObserver;
use YellowCard\OrderCommentProcessing\Queue\AddCommentToAnOrderPublisher;

class AddCommentToAnOrderObserverTest extends TestCase
{
    private $publisherMock;
    private $scopeConfigMock;
    private $observerInstance;

    protected function setUp(): void
    {
        $this->publisherMock = $this->createMock(AddCommentToAnOrderPublisher::class);
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);

        $this->observerInstance = new AddCommentToAnOrderObserver(
            $this->publisherMock,
            $this->scopeConfigMock
        );
    }

    public function testExecutePublishesOrderIdWhenEnabled(): void
    {
        $orderId = 456;

        $orderMock = $this->createMock(Order::class);
        $orderMock->method('getId')->willReturn($orderId);

        $event = new Event(['order' => $orderMock]);

        $observerMock = $this->createMock(Observer::class);
        $observerMock->method('getEvent')->willReturn($event);

        $this->scopeConfigMock->method('isSetFlag')->with('sales/order_comments/active')->willReturn(true);

        $this->publisherMock->expects($this->once())
            ->method('publish')
            ->with($orderId);

        $this->observerInstance->execute($observerMock);
    }

    public function testExecuteDoesNotPublishWhenDisabled(): void
    {
        $orderMock = $this->createMock(Order::class);

        $event = new Event(['order' => $orderMock]);

        $observerMock = $this->createMock(Observer::class);
        $observerMock->method('getEvent')->willReturn($event);

        // Make sure isEnabled() returns false
        $this->scopeConfigMock->method('isSetFlag')->with('sales/order_comments/active')->willReturn(false);

        $this->publisherMock->expects($this->never())
            ->method('publish');

        $this->observerInstance->execute($observerMock);
    }
}
