<?php

declare(strict_types=1);

namespace App\Application\Contract;

interface WarehouseModuleInterface
{
    public function executeQuery(QueryInterface $query): mixed;

    public function executeCommand(CommandInterface $command): mixed;
}
