<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\DomainEventsDispatching;

use App\Infrastructure\Configuration\DomainEventsDispatching\DomainEventsAccessor;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Domain\Entity;
use PHPUnit\Framework\TestCase;

class DomainEventsAccessorTest extends TestCase
{
    public function testThatReturnsAllDomainEventsFromPersistedEntities(): void
    {
        $insertedEntityDomainEvent = $this->createStub(DomainEventInterface::class);
        $insertedEntity = $this->createStub(Entity::class);
        $insertedEntity->method('getDomainEvents')->willReturn([$insertedEntityDomainEvent]);

        $updatedEntityDomainEvent = $this->createStub(DomainEventInterface::class);
        $updatedEntity = $this->createStub(Entity::class);
        $updatedEntity->method('getDomainEvents')->willReturn([$updatedEntityDomainEvent]);

        $deletedEntityDomainEvent = $this->createStub(DomainEventInterface::class);
        $deletedEntity = $this->createStub(Entity::class);
        $deletedEntity->method('getDomainEvents')->willReturn([$deletedEntityDomainEvent]);

        $unitOfWork = $this->createMock(UnitOfWork::class);
        $unitOfWork->expects($this->once())
            ->method('getScheduledEntityInsertions')
            ->willReturn([$insertedEntity]);

        $unitOfWork->expects($this->once())
            ->method('getScheduledEntityUpdates')
            ->willReturn([$updatedEntity]);

        $unitOfWork->expects($this->once())
            ->method('getScheduledEntityDeletions')
            ->willReturn([$deletedEntity]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWork);

        $domainEventsAccessor = new DomainEventsAccessor($entityManager);

        $domainEvents = $domainEventsAccessor->getAllDomainEvents();

        $this->assertCount(3, $domainEvents);
        $this->assertContains($insertedEntityDomainEvent, $domainEvents);
        $this->assertContains($updatedEntityDomainEvent, $domainEvents);
        $this->assertContains($deletedEntityDomainEvent, $domainEvents);
    }

    public function testThatClearsAllDomainEventsFromPersistedEntities(): void
    {
        $insertedEntityDomainEvent = $this->createStub(DomainEventInterface::class);
        $insertedEntity = $this->createMock(Entity::class);
        $insertedEntity->expects($this->once())
            ->method('clearDomainEvents');

        $updatedEntityDomainEvent = $this->createStub(DomainEventInterface::class);
        $updatedEntity = $this->createMock(Entity::class);
        $updatedEntity->expects($this->once())
            ->method('clearDomainEvents');

        $deletedEntityDomainEvent = $this->createStub(DomainEventInterface::class);
        $deletedEntity = $this->createMock(Entity::class);
        $deletedEntity->expects($this->once())
            ->method('clearDomainEvents');

        $unitOfWork = $this->createMock(UnitOfWork::class);
        $unitOfWork->expects($this->once())
            ->method('getScheduledEntityInsertions')
            ->willReturn([$insertedEntity]);

        $unitOfWork->expects($this->once())
            ->method('getScheduledEntityUpdates')
            ->willReturn([$updatedEntity]);

        $unitOfWork->expects($this->once())
            ->method('getScheduledEntityDeletions')
            ->willReturn([$deletedEntity]);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('getUnitOfWork')
            ->willReturn($unitOfWork);

        $domainEventsAccessor = new DomainEventsAccessor($entityManager);

        $domainEventsAccessor->clearAllDomainEvents();
    }
}
