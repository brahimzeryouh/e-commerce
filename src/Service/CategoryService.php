<?php
namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository->findOneBySlug($slug);
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }
}