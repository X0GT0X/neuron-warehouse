<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\DomainEventsDispatching;

use Doctrine\ORM\EntityManagerInterface;
use Neuron\BuildingBlocks\Domain\Entity;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsAccessorInterface;

final readonly class DomainEventsAccessor implements DomainEventsAccessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getAllDomainEvents(): array
    {
        $domainEvents = [];

        $persistedEntities = $this->getPersistedEntities();

        foreach ($persistedEntities as $entity) {
            if ($entity instanceof Entity) {
                \array_push($domainEvents, ...$entity->getDomainEvents());
            }
        }

        return $domainEvents;
    }

    public function clearAllDomainEvents(): void
    {
        $persistedEntities = $this->getPersistedEntities();

        foreach ($persistedEntities as $entity) {
            if ($entity instanceof Entity) {
                $entity->clearDomainEvents();
            }
        }
    }

    /** @return array<int, object> */
    private function getPersistedEntities(): array
    {
        $unitOfWork = $this->entityManager->getUnitOfWork();

        return [
            ...$unitOfWork->getScheduledEntityInsertions(),
            ...$unitOfWork->getScheduledEntityUpdates(),
            ...$unitOfWork->getScheduledEntityDeletions(),
        ];
    }
}
