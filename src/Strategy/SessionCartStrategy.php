<?php

namespace App\Strategy;

use App\DTO\CartDTO;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCartStrategy implements CartStrategyInterface
{
    private const SESSION_KEY = 'cart_dto';

    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {
    }

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    private function saveToSession(CartDTO $cart): void
    {
        $this->getSession()->set(self::SESSION_KEY, $cart->toArray());
    }

    private function loadFromSession(string $sessionId): ?CartDTO
    {
        $data = $this->getSession()->get(self::SESSION_KEY);
        if (!$data || $data['sessionId'] !== $sessionId) {
            return null;
        }
        return CartDTO::fromArray($data);
    }

    public function addItem(CartDTO $cart, int $productId, int $quantity, float $price): CartDTO
    {
        $product = $this->productRepository->find($productId);
        if (!$product) {
            return $cart;
        }

        $existingItems = $cart->getItems();
        $found = false;

        foreach ($existingItems as &$item) {
            if ($item['product_id'] === $productId) {
                $newQuantity = $item['quantity'] + $quantity;
                $cart->updateQuantity($productId, $newQuantity);
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart->addItem($productId, $product->getNom(), $quantity, $price);
        }

        $this->saveToSession($cart);
        return $cart;
    }

    public function removeItem(CartDTO $cart, int $productId): CartDTO
    {
        $cart->removeItem($productId);
        $this->saveToSession($cart);
        return $cart;
    }

    public function getCart(string $sessionId): ?CartDTO
    {
        return $this->loadFromSession($sessionId);
    }

    public function clearCart(string $sessionId): void
    {
        $this->getSession()->remove(self::SESSION_KEY);
    }

    public function createEmptyCart(string $sessionId): CartDTO
    {
        $cart = new CartDTO($sessionId);
        $this->saveToSession($cart);
        return $cart;
    }
}