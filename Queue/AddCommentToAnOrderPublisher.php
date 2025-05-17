<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Queue;

use Magento\Framework\MessageQueue\PublisherInterface;

class AddCommentToAnOrderPublisher
{
    /**
     * @var PublisherInterface
     */
    const TOPIC_NAME = 'yellowcard.order.comment';

    /**
     * @var PublisherInterface
     */
    private PublisherInterface $publisher;

    /**
     * @param PublisherInterface $publisher
     */
    public function __construct(
        PublisherInterface $publisher,
    ){
        $this->publisher = $publisher;
    }

    /**
     * @param int $orderId
     * @return void
     */
    public function publish(int $orderId): void
    {
        $this->publisher->publish(self::TOPIC_NAME, $orderId);
    }
}
