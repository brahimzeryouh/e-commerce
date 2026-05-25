<?php
namespace App\Cart;

use App\DTO\CartDTO;

interface CartInterface
{
    public function addItem(CartDTO $cart, int $productId, int $quantity, float $price): CartDTO;
    public function removeItem(CartDTO $cart, int $productId): CartDTO;
    public function getCart(string $identifier): ?CartDTO;
    public function clearCart(string $identifier): void;
}