<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\DependencyInjection;

use App\Infrastructure\Configuration\Decorator\LoggingCommandHandlerDecorator;
use App\Infrastructure\Configuration\Decorator\UnitOfWorkCommandHandlerDecorator;
use App\Infrastructure\Configuration\Decorator\ValidationCommandHandlerDecorator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CommandHandlerDecoratorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $taggedServices = $container->findTaggedServiceIds(
            'app.command_handler'
        );

        foreach ($taggedServices as $id => $tags) {
            if (UnitOfWorkCommandHandlerDecorator::class === $id
                || ValidationCommandHandlerDecorator::class === $id
                || LoggingCommandHandlerDecorator::class === $id) {
                continue;
            }

            $decoratedWithUnitOfWorkServiceId = $this->generateAliasName($id, UnitOfWorkCommandHandlerDecorator::class);
            $container->register($decoratedWithUnitOfWorkServiceId, UnitOfWorkCommandHandlerDecorator::class)
                ->setDecoratedService($id)
                ->setPublic(true)
                ->setAutowired(true);

            $decoratedWithValidationServiceId = $this->generateAliasName($id, ValidationCommandHandlerDecorator::class);
            $container->register($decoratedWithValidationServiceId, ValidationCommandHandlerDecorator::class)
                ->setDecoratedService($decoratedWithUnitOfWorkServiceId)
                ->setPublic(true)
                ->setAutowired(true);

            $decoratedWithLoggingServiceId = $this->generateAliasName($id, LoggingCommandHandlerDecorator::class);
            $container->register($decoratedWithLoggingServiceId, LoggingCommandHandlerDecorator::class)
                ->setDecoratedService($decoratedWithValidationServiceId)
                ->setPublic(true)
                ->setAutowired(true);
        }
    }

    private function generateAliasName(string $serviceName, string $decoratorName): string
    {
        $serviceAlias = $this->generateLowercaseName($serviceName);
        $decoratorAlias = $this->generateLowercaseName($decoratorName);

        return $serviceAlias.'_'.$decoratorAlias;
    }

    private function generateLowercaseName(string $serviceName): string
    {
        if (\str_contains($serviceName, '\\')) {
            $parts = \explode('\\', $serviceName);
            $className = \end($parts);
            $lowercaseName = \mb_strtolower(
                \preg_replace('/[A-Z]/', '_\0', \lcfirst($className)) ?? throw new \LogicException(\sprintf('Class name %s cannot be transformed to lowercase', $className))
            );
        } else {
            $lowercaseName = $serviceName;
        }

        return $lowercaseName;
    }
}
