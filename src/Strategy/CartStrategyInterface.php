<?php

namespace App\Strategy;

use App\DTO\CartDTO;

interface CartStrategyInterface
{
    public function addItem(CartDTO $cart, int $productId, int $quantity, float $price): CartDTO;
    public function removeItem(CartDTO $cart, int $productId): CartDTO;
    public function getCart(string $sessionId): ?CartDTO;
    public function clearCart(string $sessionId): void;
    public function createEmptyCart(string $sessionId): CartDTO;
}