<?php

declare(strict_types=1);

namespace App\Tests\IntegrationTest;

use App\Application\Contract\{{SERVICE_NAME}}ModuleInterface;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected {{SERVICE_NAME}}ModuleInterface ${{SERVICE_NAME}}Module;

    protected Connection $connection;

    protected TransportInterface $outboxTransport;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->{{SERVICE_NAME}}Module = $container->get({{SERVICE_NAME}}ModuleInterface::class);
        $this->connection = $container->get(Connection::class);
        $this->outboxTransport = $container->get('messenger.transport.outbox');
    }
}
