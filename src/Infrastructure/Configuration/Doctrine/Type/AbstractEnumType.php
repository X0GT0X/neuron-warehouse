<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class AbstractEnumType extends Type
{
    abstract public static function getEnumClass(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|int|null
    {
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?\BackedEnum
    {
        if (false === \enum_exists($this::getEnumClass())) {
            throw new \LogicException(\sprintf('Class %s should be an enum', $this::getEnumClass()));
        }

        /** @var \BackedEnum|null */
        return $this::getEnumClass()::tryFrom($value ?? ''); // @phpstan-ignore-line
    }
}
