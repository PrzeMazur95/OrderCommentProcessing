<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Test\Unit\Queue;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use YellowCard\OrderCommentProcessing\Api\AddCommentToAnOrderInterface;
use YellowCard\OrderCommentProcessing\Queue\AddCommentToAnOrderConsumer;

class AddCommentToAnOrderConsumerTest extends TestCase
{
    private $addCommentServiceMock;
    private $loggerMock;

    protected function setUp(): void
    {
        $this->addCommentServiceMock = $this->createMock(AddCommentToAnOrderInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
    }

    public function testExecuteCallsAddCommentToAnOrderWithValidOrderId(): void
    {
        $orderId = 123;

        $this->addCommentServiceMock->expects($this->once())
            ->method('execute')
            ->with($orderId);

        $this->loggerMock->expects($this->never())->method('error');

        $consumer = new AddCommentToAnOrderConsumer($this->addCommentServiceMock, $this->loggerMock);
        $consumer->execute($orderId);
    }

    public function testExecuteLogsErrorIfAddCommentFails(): void
    {
        $orderId = 456;
        $exceptionMessage = 'Something went wrong';

        $this->addCommentServiceMock->expects($this->once())
            ->method('execute')
            ->with($orderId)
            ->willThrowException(new \Exception($exceptionMessage));

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with(
                'Error adding comment to order with id ' . $orderId,
                [$exceptionMessage]
            );

        $consumer = new AddCommentToAnOrderConsumer($this->addCommentServiceMock, $this->loggerMock);
        $consumer->execute($orderId);
    }

    public function testExecuteLogsErrorIfOrderIdIsNull(): void
    {
        $this->addCommentServiceMock->expects($this->never())->method('execute');

        $this->loggerMock->expects($this->once())
            ->method('error')
            ->with('Order ID is null or invalid.');

        $consumer = new AddCommentToAnOrderConsumer($this->addCommentServiceMock, $this->loggerMock);
        $consumer->execute(null);
    }
}

