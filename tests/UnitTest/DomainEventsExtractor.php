<?php

declare(strict_types=1);

namespace App\Tests\UnitTest;

use Neuron\BuildingBlocks\Domain\DomainEventInterface;
use Neuron\BuildingBlocks\Domain\Entity;

class DomainEventsExtractor
{
    /**
     * @throws \ReflectionException
     *
     * @return DomainEventInterface[]
     */
    public static function getAllDomainEvents(Entity $aggregate): array
    {
        /** @var DomainEventInterface[] $domainEvents */
        $domainEvents = [];

        \array_push($domainEvents, ...$aggregate->getDomainEvents());

        $ref = new \ReflectionClass($aggregate);

        $properties = self::getAllClassProperties($ref);

        foreach ($properties as $property) {
            $isEntity = self::isEntity($property);

            if ($isEntity) {
                $entity = $property->getValue($aggregate);

                if ($entity instanceof Entity) {
                    \array_push($domainEvents, ...self::getAllDomainEvents($entity));
                }
            }

            if ($property->isInitialized($aggregate) && \is_iterable($iterable = $property->getValue($aggregate))) {
                foreach ($iterable as $value) {
                    if ($value instanceof Entity) {
                        \array_push($domainEvents, ...self::getAllDomainEvents($value));
                    }
                }
            }
        }

        return $domainEvents;
    }

    /** @throws \ReflectionException */
    private static function isEntity(\ReflectionProperty $property): bool
    {
        /** @var \ReflectionNamedType|null $propertyType */
        $propertyType = $property->getType();

        if ($propertyType?->isBuiltin()) {
            return false;
        }

        /** @var class-string<object> $propertyTypeName */
        $propertyTypeName = $propertyType?->getName();
        $propertyRef = new \ReflectionClass($propertyTypeName);

        return $propertyRef->isSubclassOf(Entity::class); // @phpstan-ignore-line
    }

    /**
     * @param \ReflectionClass<Entity> $class
     *
     * @return \ReflectionProperty[]
     */
    private static function getAllClassProperties(\ReflectionClass $class): array
    {
        /** @var \ReflectionProperty[] $properties */
        $properties = [];

        \array_push($properties, ...$class->getProperties());

        if ($parent = $class->getParentClass()) {
            \array_push($properties, ...self::getAllClassProperties($parent));
        }

        return $properties;
    }
}
