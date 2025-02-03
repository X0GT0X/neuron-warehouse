<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Outbox;

use App\Infrastructure\Configuration\Outbox\OutboxMessageHandler;
use Neuron\BuildingBlocks\Application\Event\DomainEventNotificationInterface;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class OutboxMessageHandlerTest extends TestCase
{
    public function testThatHandlesOutboxMessageProperly(): void
    {
        $outboxMessage = new OutboxMessage(
            Uuid::fromString('7f63d7a0-29c8-43c5-b618-83a9a343130a'),
            new \DateTimeImmutable('2023-04-24 09:10:34'),
            'NotificationType',
            'serialized_notification'
        );
        $notification = $this->createStub(DomainEventNotificationInterface::class);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->expects($this->once())
            ->method('dispatch')
            ->with($notification)
            ->willReturn(new Envelope($notification));

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($outboxMessage->data, $outboxMessage->type, 'json')
            ->willReturn($notification);

        $handler = new OutboxMessageHandler($eventBus, $serializer);

        $handler($outboxMessage);
    }
}
