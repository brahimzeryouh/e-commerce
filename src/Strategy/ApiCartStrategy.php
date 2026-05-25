<?php

namespace App\Strategy;

use App\DTO\CartDTO;
use Symfony\Component\VarDumper\VarDumper;

class ApiCartStrategy implements CartStrategyInterface
{
    public function addItem(CartDTO $cart, int $productId, int $quantity, float $price): CartDTO
    {
        VarDumper::dump([
            'api_call' => 'addItem',
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);

        $cart->addItem($productId, 'Product #' . $productId, $quantity, $price);
        return $cart;
    }

    public function removeItem(CartDTO $cart, int $productId): CartDTO
    {
        VarDumper::dump([
            'api_call' => 'removeItem',
            'product_id' => $productId
        ]);

        $cart->removeItem($productId);
        return $cart;
    }

    public function getCart(string $sessionId): ?CartDTO
    {
        VarDumper::dump([
            'api_call' => 'getCart',
            'session_id' => $sessionId
        ]);

        return new CartDTO($sessionId);
    }

    public function clearCart(string $sessionId): void
    {
        VarDumper::dump([
            'api_call' => 'clearCart',
            'session_id' => $sessionId
        ]);
    }

    public function createEmptyCart(string $sessionId): CartDTO
    {
        VarDumper::dump([
            'api_call' => 'createEmptyCart',
            'session_id' => $sessionId
        ]);

        return new CartDTO($sessionId);
    }
}