<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Outbox;

use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxInterface;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class Outbox implements OutboxInterface
{
    public function __construct(
        private MessageBusInterface $outboxBus
    ) {
    }

    public function add(OutboxMessage $message): void
    {
        $this->outboxBus->dispatch($message);
    }
}
