<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\DomainEventsDispatching;

use App\Infrastructure\Configuration\DomainEventsDispatching\DomainEventsDispatcher;
use Neuron\BuildingBlocks\Application\Event\AbstractDomainEventNotification;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventNotificationsResolverInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsAccessorInterface;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxInterface;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class DomainEventsDispatcherTest extends TestCase
{
    public function testThatDispatchesDomainEventsAndAddsNotificationsToOutbox(): void
    {
        $domainEvent = $this->createStub(DomainEventInterface::class);
        $domainEvent->method('getId')->willReturn(Uuid::fromString('7f63d7a0-29c8-43c5-b618-83a9a343130a'));
        $domainEvent->method('getOccurredOn')->willReturn(new \DateTimeImmutable('2023-04-24 09:10:34'));

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->expects($this->once())
            ->method('dispatch')
            ->with($domainEvent)
            ->willReturn(new Envelope($domainEvent));

        $outbox = $this->createMock(OutboxInterface::class);
        $outbox->expects($this->once())
            ->method('add')
            ->with($this->callback(function(OutboxMessage $outboxMessage) use ($domainEvent): bool {
                $this->assertEquals($domainEvent->getId(), $outboxMessage->id);
                $this->assertEquals($domainEvent->getOccurredOn(), $outboxMessage->occurredOn);
                $this->assertEquals(DomainEventNotificationStub::class, $outboxMessage->type);
                $this->assertEquals('serialized_notification', $outboxMessage->data);

                return true;
            }));

        $domainEventsAccessor = $this->createMock(DomainEventsAccessorInterface::class);
        $domainEventsAccessor->expects($this->once())
            ->method('getAllDomainEvents')
            ->willReturn([$domainEvent]);

        $domainEventsAccessor->expects($this->once())
            ->method('clearAllDomainEvents');

        $notificationsResolver = $this->createMock(DomainEventNotificationsResolverInterface::class);
        $notificationsResolver->expects($this->once())
            ->method('getNotificationTypeByDomainEvent')
            ->with($domainEvent)
            ->willReturn(DomainEventNotificationStub::class);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('serialize')
            ->willReturn('serialized_notification');

        $domainEventsDispatcher = new DomainEventsDispatcher(
            $eventBus,
            $outbox,
            $domainEventsAccessor,
            $notificationsResolver,
            $serializer
        );

        $domainEventsDispatcher->dispatch();
    }
}

readonly class DomainEventNotificationStub extends AbstractDomainEventNotification
{
}
