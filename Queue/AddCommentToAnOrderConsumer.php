<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Queue;

use Psr\Log\LoggerInterface;
use YellowCard\OrderCommentProcessing\Api\AddCommentToAnOrderInterface;

class AddCommentToAnOrderConsumer
{
    /**
     * @var AddCommentToAnOrderInterface
     */
    private AddCommentToAnOrderInterface $addCommentToAnOrder;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param AddCommentToAnOrderInterface $addCommentToAnOrder
     * @param LoggerInterface $logger
     */
    public function __construct(
        AddCommentToAnOrderInterface $addCommentToAnOrder,
        LoggerInterface $logger
    ) {
        $this->addCommentToAnOrder = $addCommentToAnOrder;
        $this->logger = $logger;
    }

    /**
     * Adds comment set in the system configuration to the order with the given ID.
     *
     * @param $orderId
     * @return void
     */
    public function execute($orderId): void
    {
        $orderId = $orderId ?? null;

        if ($orderId) {
            try {
                $this->addCommentToAnOrder->execute($orderId);
            } catch (\Exception $e) {
                $this->logger->error('Error adding comment to order with id ' . $orderId, [$e->getMessage()]);
            }
        } else {
            $this->logger->error('Order ID is null or invalid.');
        }
    }
}
