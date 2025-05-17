<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order;
use YellowCard\OrderCommentProcessing\Model\AddCommentToAnOrder;

class AddCommentToAnOrderTest extends TestCase
{
    private $orderRepositoryMock;
    private $scopeConfigMock;
    private $orderMock;
    private $service;

    protected function setUp(): void
    {
        $this->orderRepositoryMock = $this->createMock(OrderRepositoryInterface::class);
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->orderMock = $this->createMock(Order::class);

        $this->service = new AddCommentToAnOrder(
            $this->orderRepositoryMock,
            $this->scopeConfigMock
        );
    }

    public function testExecuteAddsCommentToOrder(): void
    {
        $orderId = 1;
        $comment = 'Test comment';

        $this->scopeConfigMock->method('getValue')
            ->with('sales/order_comments/default_comment')
            ->willReturn($comment);

        $this->orderMock->expects($this->once())
            ->method('addCommentToStatusHistory')
            ->with($comment);

        $this->orderRepositoryMock->expects($this->once())
            ->method('get')
            ->with($orderId)
            ->willReturn($this->orderMock);

        $this->orderRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->orderMock);

        $this->service->execute($orderId);
    }
}
