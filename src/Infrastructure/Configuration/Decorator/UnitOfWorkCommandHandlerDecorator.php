<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use Neuron\BuildingBlocks\Infrastructure\UnitOfWorkInterface;

final readonly class UnitOfWorkCommandHandlerDecorator implements CommandHandlerInterface
{
    public function __construct(
        private CommandHandlerInterface $inner,
        private UnitOfWorkInterface $unitOfWork,
    ) {
    }

    public function __invoke(CommandInterface $command): mixed
    {
        $result = $this->inner->__invoke($command);

        $this->unitOfWork->commit();

        return $result;
    }
}
