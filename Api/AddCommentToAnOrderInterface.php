<?php

declare(strict_types=1);

namespace YellowCard\OrderCommentProcessing\Api;

interface AddCommentToAnOrderInterface
{
    public function execute(int $orderId): void;
}
