<?php

namespace App\Handler;

use App\DTO\CartDTO;
use App\Strategy\CartStrategyInterface;

class CartHandler
{
    public function __construct(private CartStrategyInterface $strategy)
    {
    }

    public function addItem(CartDTO $cart, int $productId, int $quantity, float $price): CartDTO
    {
        return $this->strategy->addItem($cart, $productId, $quantity, $price);
    }

    public function removeItem(CartDTO $cart, int $productId): CartDTO
    {
        return $this->strategy->removeItem($cart, $productId);
    }

    public function getCart(string $sessionId): ?CartDTO
    {
        return $this->strategy->getCart($sessionId);
    }

    public function clearCart(string $sessionId): void
    {
        $this->strategy->clearCart($sessionId);
    }

    public function createEmptyCart(string $sessionId): CartDTO
    {
        return $this->strategy->createEmptyCart($sessionId);
    }
}