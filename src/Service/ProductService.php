<?php
namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function getProductsByCategorySlug(string $slug): array
    {
        return $this->productRepository->findByCategorySlug($slug);
    }

    public function getProductsInStock(): array
    {
        return $this->productRepository->findInStock();
    }
}