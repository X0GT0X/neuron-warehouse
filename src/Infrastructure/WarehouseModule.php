<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\Contract\CommandInterface;
use App\Application\Contract\QueryInterface;
use App\Application\Contract\WarehouseModuleInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class WarehouseModule implements WarehouseModuleInterface
{
    use HandleTrait;

    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly MessageBusInterface $queryBus,
    ) {
    }

    public function executeQuery(QueryInterface $query): mixed
    {
        return $this->handleQuery($query);
    }

    public function executeCommand(CommandInterface $command): mixed
    {
        return $this->handleCommand($command);
    }
}
