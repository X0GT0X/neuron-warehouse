<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Inbox;

use App\Infrastructure\Configuration\Inbox\InboxMessageHandler;
use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxMessage;
use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class InboxMessageHandlerTest extends TestCase
{
    public function testThatHandlesInboxMessageProperly(): void
    {
        $inboxMessage = new InboxMessage(
            Uuid::fromString('7f63d7a0-29c8-43c5-b618-83a9a343130a'),
            new \DateTimeImmutable('2023-04-24 09:10:34'),
            'serialized_data'
        );
        $integrationEvent = $this->createStub(IntegrationEvent::class);

        $eventBus = $this->createMock(MessageBusInterface::class);
        $eventBus->expects($this->once())
            ->method('dispatch')
            ->with($integrationEvent)
            ->willReturn(new Envelope($integrationEvent));

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('deserialize')
            ->with($inboxMessage->data, IntegrationEvent::class, 'json')
            ->willReturn($integrationEvent);

        $handler = new InboxMessageHandler($eventBus, $serializer);

        $handler($inboxMessage);
    }
}
