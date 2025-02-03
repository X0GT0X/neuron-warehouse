<?php

declare(strict_types=1);

namespace App\Infrastructure\Domain\Product;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Product\Product;
use App\Domain\Product\ProductId;
use App\Domain\Product\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of Product
 *
 * @template-extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    public function get(ProductId $id): Product
    {
        $product = $this->find($id);

        if (!$product instanceof Product) {
            throw new EntityNotFoundException(\sprintf('Product with ID \'%s\' not found', $id->getValue()->toString()));
        }

        return $product;
    }
}
