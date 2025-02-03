<?php

declare(strict_types=1);

namespace App\Tests\UnitTest\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Contract\CommandInterface;
use App\Infrastructure\Configuration\Decorator\LoggingCommandHandlerDecorator;
use App\Tests\UnitTest\UnitTestCase;
use Neuron\BuildingBlocks\Domain\BusinessRuleInterface;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Psr\Log\LoggerInterface;

class LoggingCommandHandlerDecoratorTest extends UnitTestCase
{
    public function testThatLogsCommandStartAndFinish(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $inner = new class() implements CommandHandlerInterface {
            public function __invoke(CommandInterface $command): string
            {
                return 'result';
            }
        };

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))
            ->method('info')
            ->with($this->logicalOr(
                $this->equalTo(\sprintf('Executing command %s', $command::class)),
                $this->equalTo(\sprintf('Command %s processed successfully', $command::class)),
            ));

        $decorator = new LoggingCommandHandlerDecorator($inner, $logger);

        $result = $decorator->__invoke($command);

        $this->assertEquals('result', $result);
    }

    public function testThatLogsCommandFailure(): void
    {
        $command = $this->createStub(CommandInterface::class);

        $brokenRule = $this->createStub(BusinessRuleInterface::class);
        $exception = new BusinessRuleValidationException($brokenRule);

        $inner = new readonly class($exception) implements CommandHandlerInterface {
            public function __construct(
                private BusinessRuleValidationException $exception
            ) {
            }

            public function __invoke(CommandInterface $command): string
            {
                throw $this->exception;
            }
        };

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('error')
            ->with(\sprintf('Command %s processing failed', $command::class), [
                'exception' => $exception,
            ]);

        $decorator = new LoggingCommandHandlerDecorator($inner, $logger);

        $this->expectExceptionObject($exception);
        $decorator->__invoke($command);
    }

    public function testThatLogsUnexpectedError(): void
    {
        $command = $this->createStub(CommandInterface::class);
        $exception = $this->createStub(\Exception::class);

        $inner = new readonly class($exception) implements CommandHandlerInterface {
            public function __construct(
                private \Exception $exception
            ) {
            }

            public function __invoke(CommandInterface $command): string
            {
                throw $this->exception;
            }
        };

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())
            ->method('critical')
            ->with(\sprintf('Command %s processing failed with unexpected error', $command::class), [
                'exception' => $exception,
            ]);

        $decorator = new LoggingCommandHandlerDecorator($inner, $logger);

        $this->expectExceptionObject($exception);
        $decorator->__invoke($command);
    }
}
