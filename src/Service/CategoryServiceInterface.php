<?php
namespace App\Service;

use App\Entity\Category;

interface CategoryServiceInterface
{
    /**
     * @return Category[]
     */
    public function getAllCategories(): array;

    /**
     * @param string $slug
     * @return Category|null
     */
    public function getCategoryBySlug(string $slug): ?Category;

    /**
     * @param int $id
     * @return Category|null
     */
    public function getCategoryById(int $id): ?Category;
}