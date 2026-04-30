<?php
namespace App\Cart;

class CartHandler
{
    public function __construct(private CartInterface $cart) {}

    public function addToCart(int $productId, int $quantity): void
    {
        $this->cart->add($productId, $quantity);
    }

    public function getCart(): array
    {
        return $this->cart->getItems();
    }
}
