<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Test\Unit\Queue;

use Magento\Framework\MessageQueue\PublisherInterface;
use PHPUnit\Framework\TestCase;
use YellowCard\OrderCommentProcessing\Queue\AddCommentToAnOrderPublisher;

class AddCommentToAnOrderPublisherTest extends TestCase
{
    private $publisherMock;

    protected function setUp(): void
    {
        $this->publisherMock = $this->createMock(PublisherInterface::class);
    }

    public function testPublishSendsMessageToCorrectTopic(): void
    {
        $orderId = 789;
        $expectedTopic = 'yellowcard.order.comment';

        $this->publisherMock->expects($this->once())
            ->method('publish')
            ->with($expectedTopic, $orderId);

        $subject = new AddCommentToAnOrderPublisher($this->publisherMock);
        $subject->publish($orderId);
    }
}
