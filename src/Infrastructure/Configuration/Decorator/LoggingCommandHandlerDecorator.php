<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Psr\Log\LoggerInterface;

final readonly class LoggingCommandHandlerDecorator implements CommandHandlerInterface
{
    public function __construct(
        private CommandHandlerInterface $inner,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(CommandInterface $command): mixed
    {
        $this->logger->info(\sprintf('Executing command %s', $command::class));

        try {
            $result = $this->inner->__invoke($command);

            $this->logger->info(\sprintf('Command %s processed successfully', $command::class));

            return $result;
        } catch (\Throwable $exception) {
            if ($exception instanceof BusinessRuleValidationException) {
                $this->logger->error(\sprintf('Command %s processing failed', $command::class), [
                    'exception' => $exception,
                ]);
            }

            $this->logger->critical(\sprintf('Command %s processing failed with unexpected error', $command::class), [
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
