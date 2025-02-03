<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Money\Currency;

class CurrencyType extends AbstractEnumType
{
    public const string NAME = 'currency';

    public static function getEnumClass(): string
    {
        return Currency::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
