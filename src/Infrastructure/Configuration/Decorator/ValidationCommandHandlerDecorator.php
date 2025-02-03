<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Decorator;

use App\Application\Configuration\Command\CommandHandlerInterface;
use App\Application\Configuration\Command\InvalidCommandException;
use App\Application\Contract\CommandInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ValidationCommandHandlerDecorator implements CommandHandlerInterface
{
    public function __construct(
        private CommandHandlerInterface $inner,
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * @throws InvalidCommandException
     */
    public function __invoke(CommandInterface $command): mixed
    {
        $constraintViolationList = $this->validator->validate($command);
        $constraintViolations = $this->transformConstraintViolationListToArray($constraintViolationList);

        if (\count($constraintViolations) > 0) {
            $errors = \array_map(static fn (ConstraintViolationInterface $constraintViolation) => [
                'property' => $constraintViolation->getPropertyPath(),
                'message' => $constraintViolation->getMessage(),
            ], $constraintViolations);

            throw new InvalidCommandException($errors);
        }

        return $this->inner->__invoke($command);
    }

    /**
     * @return ConstraintViolationInterface[]
     */
    private function transformConstraintViolationListToArray(ConstraintViolationListInterface $violationList): array
    {
        $violations = [];

        foreach ($violationList as $violation) {
            $violations[] = $violation;
        }

        return $violations;
    }
}
