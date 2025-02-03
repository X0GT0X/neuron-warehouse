<?php

declare(strict_types=1);

namespace App\Infrastructure\Configuration\Doctrine\Type;

use App\Domain\Product\ProductId;

class ProductIdType extends AbstractIdType
{
    public const string NAME = 'product_id';

    public static function getIdClass(): string
    {
        return ProductId::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
