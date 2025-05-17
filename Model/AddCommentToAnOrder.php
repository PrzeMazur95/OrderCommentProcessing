<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use YellowCard\OrderCommentProcessing\Api\AddCommentToAnOrderInterface;

class AddCommentToAnOrder implements AddCommentToAnOrderInterface
{
    const COMMENT_CONFIG_PATH = 'sales/order_comments/default_comment';
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ScopeConfigInterface     $scopeConfig,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Adds comment set in the system configuration to the order with the given ID.
     *
     * @param int $orderId
     * @return void
     */
    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->get($orderId);
        $comment = $this->getCommentFromSettings();
        $order->addCommentToStatusHistory($comment);
        $this->orderRepository->save($order);
    }

    /**
     * @return string
     */
    private function getCommentFromSettings(): string
    {
        return $this->scopeConfig->getValue(self::COMMENT_CONFIG_PATH);
    }
}
