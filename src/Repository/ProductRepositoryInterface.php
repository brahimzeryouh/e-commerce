<?php
namespace App\Repository;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Product|null
     */
    public function find(int $id): ?object;

    /**
     * @param array $criteria
     * @return Product[]
     */
    public function findBy(array $criteria): array;

    /**
     * @param string $slug
     * @return Product[]
     */
    public function findByCategorySlug(string $slug): array;

    /**
     * @return Product[]
     */
    public function findInStock(): array;
}