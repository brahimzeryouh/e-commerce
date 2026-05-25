<?php
namespace App\Repository;

use App\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?object;

    /**
     * @param string $slug
     * @return Category|null
     */
    public function findOneBySlug(string $slug): ?Category;
}