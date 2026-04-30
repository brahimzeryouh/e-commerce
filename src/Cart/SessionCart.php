<?php
namespace App\Cart;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionCart implements CartInterface
{
    private const SESSION_KEY = 'cart';

    public function __construct(private RequestStack $requestStack) {}

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    public function add(int $productId, int $quantity): void
    {
        $cart = $this->getItems();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $this->getSession()->set(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->getItems();
        unset($cart[$productId]);
        $this->getSession()->set(self::SESSION_KEY, $cart);
    }

    public function getItems(): array
    {
        return $this->getSession()->get(self::SESSION_KEY, []);
    }

    public function clear(): void
    {
        $this->getSession()->remove(self::SESSION_KEY);
    }
}
