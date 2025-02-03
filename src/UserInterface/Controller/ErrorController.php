<?php

declare(strict_types=1);

namespace App\UserInterface\Controller;

use App\Application\Configuration\Command\InvalidCommandException;
use App\Domain\Exception\EntityNotFoundException;
use Neuron\BuildingBlocks\Domain\BusinessRuleValidationException;
use Neuron\BuildingBlocks\UserInterface\Request\Validation\RequestValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorController
{
    /** @throws \Throwable */
    public function __invoke(\Throwable $exception): JsonResponse
    {
        if ($exception instanceof RequestValidationException) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
                'errors' => $exception->getErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof InvalidCommandException) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
                'errors' => $exception->getErrors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($exception instanceof BusinessRuleValidationException || $exception instanceof EntityNotFoundException) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], Response::HTTP_CONFLICT);
        }

        throw $exception;
    }
}
