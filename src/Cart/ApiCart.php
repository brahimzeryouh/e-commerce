<?php
namespace App\Cart;

class ApiCart implements CartInterface
{
    public function add(int $productId, int $quantity): void
    {
        dd('ApiCart::add appelé', $productId, $quantity);
    }

    public function remove(int $productId): void
    {
        dd('ApiCart::remove appelé', $productId);
    }

    public function getItems(): array
    {
        dd('ApiCart::getItems appelé');
    }

    public function clear(): void
    {
        dd('ApiCart::clear appelé');
    }
}
