<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\EventBus;

use App\Infrastructure\Configuration\EventBus\IntegrationEventHandler;
use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxInterface;
use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxMessage;
use Neuron\BuildingBlocks\Integration\IntegrationEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class IntegrationEventHandlerTest extends TestCase
{
    public function testThatAddsEventToInbox(): void
    {
        $event = $this->createStub(IntegrationEvent::class);

        $inbox = $this->createMock(InboxInterface::class);
        $inbox->expects($this->once())
            ->method('add')
            ->with($this->callback(function(InboxMessage $inboxMessage) use ($event): bool {
                $this->assertEquals($event->getId(), $inboxMessage->id);
                $this->assertEquals($event->getOccurredOn(), $inboxMessage->occurredOn);
                $this->assertEquals('serialized_event', $inboxMessage->data);

                return true;
            }));

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects($this->once())
            ->method('serialize')
            ->with($event, 'json')
            ->willReturn('serialized_event');

        $handler = new IntegrationEventHandler($inbox, $serializer);

        $handler($event);
    }
}
