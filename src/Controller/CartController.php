<?php

namespace App\Controller;

use App\DTO\CartDTO;
use App\Handler\CartHandler;
use App\Repository\ProductRepository;
use App\Strategy\SessionCartStrategy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function show(
            #[Autowire(service: SessionCartStrategy::class)] CartHandler $cartHandler
    ): Response {
        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);

        if (!$cart) {
            $cart = $cartHandler->createEmptyCart($sessionId);
        }

        return $this->render('cart/cart.html.twig', [
                'cart' => $cart,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(
            int $id,
            ProductRepository $productRepository,
            #[Autowire(service: SessionCartStrategy::class)] CartHandler $cartHandler
    ): Response {
        $product = $productRepository->find($id);

        if (!$product) {
            $this->addFlash('error', 'Product not found');
            return $this->redirectToRoute('app_products');
        }

        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);

        if (!$cart) {
            $cart = $cartHandler->createEmptyCart($sessionId);
        }

        $cart = $cartHandler->addItem($cart, $id, 1, $product->getPrix());

        $this->addFlash('success', 'Product added to cart');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(
            int $id,
            #[Autowire(service: SessionCartStrategy::class)] CartHandler $cartHandler
    ): Response {
        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);

        if ($cart) {
            $cart = $cartHandler->removeItem($cart, $id);
            $this->addFlash('success', 'Product removed from cart');
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function updateQuantity(
            int $id,
            Request $request,
            #[Autowire(service: SessionCartStrategy::class)] CartHandler $cartHandler
    ): Response {
        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);
        $quantity = (int) $request->request->get('quantity', 1);

        if ($cart && $quantity > 0) {
            $cart->updateQuantity($id, $quantity);

            // Réappliquer les modifications
            $cartHandler->clearCart($sessionId);
            $newCart = $cartHandler->createEmptyCart($sessionId);
            foreach ($cart->getItems() as $item) {
                $newCart = $cartHandler->addItem(
                        $newCart,
                        $item['product_id'],
                        $item['quantity'],
                        $item['price']
                );
            }
            $this->addFlash('success', 'Quantity updated');
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clear(
            #[Autowire(service: SessionCartStrategy::class)] CartHandler $cartHandler
    ): Response {
        $cartHandler->clearCart(session_id());
        $this->addFlash('success', 'Cart cleared');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/checkout', name: 'app_cart_checkout')]
    public function checkout(
            #[Autowire(service: SessionCartStrategy::class)] CartHandler $cartHandler
    ): Response {
        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);

        if (!$cart || count($cart->getItems()) === 0) {
            $this->addFlash('warning', 'Your cart is empty');
            return $this->redirectToRoute('app_products');
        }

        // Simulation de validation de commande
        $this->addFlash('success', 'Order confirmed! Thank you for your purchase.');
        $cartHandler->clearCart($sessionId);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/cart/apply-promo', name: 'app_cart_apply_promo', methods: ['POST'])]
    public function applyPromo(Request $request): Response
    {
        $promoCode = $request->request->get('promo_code');

        if ($promoCode === 'DISCOUNT10') {
            $this->addFlash('success', 'Promo code applied: 10% discount');
        } else {
            $this->addFlash('error', 'Invalid promo code');
        }

        return $this->redirectToRoute('app_cart');
    }
}