<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Inbox;

use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxMessage;
use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
final readonly class InboxMessageHandler
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(InboxMessage $message): void
    {
        $event = $this->serializer->deserialize($message->data, IntegrationEvent::class, 'json');

        $this->eventBus->dispatch($event);
    }
}
