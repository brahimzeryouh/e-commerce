<?php
namespace App\Service;

use App\Entity\Product;

interface ProductServiceInterface
{
    /**
     * @return Product[]
     */
    public function getAllProducts(): array;

    /**
     * @param int $id
     * @return Product|null
     */
    public function getProductById(int $id): ?Product;

    /**
     * @param string $slug
     * @return Product[]
     */
    public function getProductsByCategorySlug(string $slug): array;

    /**
     * @return Product[]
     */
    public function getProductsInStock(): array;
}