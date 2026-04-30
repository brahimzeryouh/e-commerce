<?php

namespace App\Cart;

interface CartInterface
{
    public function add(int $productId, int $quantity): void;
    public function remove(int $productId): void;
    public function getItems(): array;
    public function clear(): void;
}
