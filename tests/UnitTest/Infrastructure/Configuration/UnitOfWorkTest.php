<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration;

use App\Infrastructure\Configuration\UnitOfWork;
use Doctrine\ORM\EntityManagerInterface;
use Neuron\BuildingBlocks\Infrastructure\DomainEventsDispatching\DomainEventsDispatcherInterface;
use PHPUnit\Framework\TestCase;

class UnitOfWorkTest extends TestCase
{
    public function testThatDispatchesDomainEventsAndFlushesEntities(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('flush');

        $domainEventsDispatcher = $this->createMock(DomainEventsDispatcherInterface::class);
        $domainEventsDispatcher->expects($this->once())
            ->method('dispatch');

        $unitOfWork = new UnitOfWork($entityManager, $domainEventsDispatcher);

        $unitOfWork->commit();
    }
}
