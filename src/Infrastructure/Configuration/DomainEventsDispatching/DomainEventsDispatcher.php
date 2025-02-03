<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\DomainEventsDispatching;

use Neuron\BuildingBlocks\Application\Event\DomainEventNotificationInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationNotFoundException;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationsResolverInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsAccessorInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsDispatcherInterface;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxInterface;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class DomainEventsDispatcher implements DomainEventsDispatcherInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private OutboxInterface $outbox,
        private DomainEventsAccessorInterface $domainEventsAccessor,
        private DomainEventNotificationsResolverInterface $notificationsResolver,
        private SerializerInterface $serializer,
    ) {
    }

    public function dispatch(): void
    {
        $domainEvents = $this->domainEventsAccessor->getAllDomainEvents();

        /** @var DomainEventNotificationInterface[] $domainEventNotifications */
        $domainEventNotifications = [];

        foreach ($domainEvents as $domainEvent) {
            try {
                $domainEventNotificationClass = $this->notificationsResolver->getNotificationTypeByDomainEvent($domainEvent);

                /** @var DomainEventNotificationInterface $domainEventNotification */
                $domainEventNotification = new $domainEventNotificationClass($domainEvent->getId(), $domainEvent);
                $domainEventNotifications[] = $domainEventNotification;
            } catch (DomainEventNotificationNotFoundException) {
                continue;
            }
        }

        $this->domainEventsAccessor->clearAllDomainEvents();

        foreach ($domainEvents as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }

        foreach ($domainEventNotifications as $domainEventNotification) {
            $this->outbox->add(new OutboxMessage(
                $domainEventNotification->getId(),
                $domainEventNotification->getDomainEvent()->getOccurredOn(),
                $domainEventNotification::class,
                $this->serializer->serialize($domainEventNotification, 'json')
            ));
        }
    }
}
