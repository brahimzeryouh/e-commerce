<?php
namespace App\Cart;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartHandler
{
    public function __construct(
        #[Autowire(service: SessionCart::class)]
        private CartInterface $cart
    ) {}

    public function addToCart(int $productId, int $quantity): void
    {
        $this->cart->add($productId, $quantity);
    }

    public function getCart(): array
    {
        return $this->cart->getItems();
    }
}
