<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Inbox;

use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxInterface;
use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxMessage;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class Inbox implements InboxInterface
{
    public function __construct(
        private MessageBusInterface $inboxBus
    ) {
    }

    public function add(InboxMessage $message): void
    {
        $this->inboxBus->dispatch($message);
    }
}
