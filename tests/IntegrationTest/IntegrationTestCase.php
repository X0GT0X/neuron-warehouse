<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTest;

use App\Application\Contract\WarehouseModuleInterface;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected WarehouseModuleInterface $warehouseModule;

    protected Connection $connection;

    protected TransportInterface $outboxTransport;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->warehouseModule = $container->get(WarehouseModuleInterface::class);
        $this->connection = $container->get(Connection::class);
        $this->outboxTransport = $container->get('messenger.transport.outbox');
    }
}
