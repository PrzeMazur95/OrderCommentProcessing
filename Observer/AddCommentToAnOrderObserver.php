<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use YellowCard\OrderCommentProcessing\Queue\AddCommentToAnOrderPublisher;

class AddCommentToAnOrderObserver implements ObserverInterface
{
    /**
     * @var AddCommentToAnOrderPublisher
     */
    private AddCommentToAnOrderPublisher $publisher;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param AddCommentToAnOrderPublisher $publisher
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        AddCommentToAnOrderPublisher $publisher,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->publisher = $publisher;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Send Order ID to publisher, to be added to the queue, to add comment to the order by its consumer.
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        if ($this->isEnabled()) {
            $orderId = $observer->getEvent()->getOrder()->getId();
            $this->publisher->publish((int)$orderId);
        }
    }

    /**
     * @return bool
     */
    private function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag('sales/order_comments/active');
    }
}
