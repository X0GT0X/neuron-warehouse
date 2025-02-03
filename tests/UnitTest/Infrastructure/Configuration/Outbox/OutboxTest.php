<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Outbox;

use App\Infrastructure\Configuration\Outbox\Outbox;
use Neuron\BuildingBlocks\Infrastructure\Outbox\OutboxMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class OutboxTest extends TestCase
{
    public function testThatDispatchesMessageWithOutboxBus(): void
    {
        $outboxMessage = new OutboxMessage(
            Uuid::fromString('7f63d7a0-29c8-43c5-b618-83a9a343130a'),
            new \DateTimeImmutable('2023-04-24 09:10:34'),
            'NotificationType',
            'serialized_notification'
        );

        $outboxBus = $this->createMock(MessageBusInterface::class);
        $outboxBus->expects($this->once())
            ->method('dispatch')
            ->with($outboxMessage)
            ->willReturn(new Envelope($outboxMessage));

        $outbox = new Outbox($outboxBus);

        $outbox->add($outboxMessage);
    }
}
