<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Outbox;

use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
readonly class OutboxMessageHandler
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private SerializerInterface $serializer
    ) {
    }

    public function __invoke(OutboxMessage $message): void
    {
        /** @var object $notification */
        $notification = $this->serializer->deserialize($message->data, $message->type, 'json');

        $this->eventBus->dispatch($notification);
    }
}
