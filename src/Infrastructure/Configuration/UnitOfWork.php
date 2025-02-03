<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration;

use Doctrine\ORM\EntityManagerInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsDispatcherInterface;
use Neuron\BuildingBlocks\Infrastructure\UnitOfWorkInterface;

final readonly class UnitOfWork implements UnitOfWorkInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DomainEventsDispatcherInterface $domainEventsDispatcher,
    ) {
    }

    public function commit(): void
    {
        $this->domainEventsDispatcher->dispatch();

        $this->entityManager->flush();
    }
}
