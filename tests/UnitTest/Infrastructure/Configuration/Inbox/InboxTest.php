<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Inbox;

use App\Infrastructure\Configuration\Inbox\Inbox;
use Neuron\BuildingBlocks\Infrastructure\Inbox\InboxMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class InboxTest extends TestCase
{
    public function testThatDispatchesMessageWithInboxBus(): void
    {
        $inboxMessage = new InboxMessage(
            Uuid::fromString('7f63d7a0-29c8-43c5-b618-83a9a343130a'),
            new \DateTimeImmutable('2023-04-24 09:10:34'),
            'serialized_data'
        );

        $inboxBus = $this->createMock(MessageBusInterface::class);
        $inboxBus->expects($this->once())
            ->method('dispatch')
            ->with($inboxMessage)
            ->willReturn(new Envelope($inboxMessage));

        $inbox = new Inbox($inboxBus);

        $inbox->add($inboxMessage);
    }
}
