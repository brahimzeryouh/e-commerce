<?php
namespace App\Controller;

use App\Handler\CartHandler;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('category/browse_categories.html.twig');
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(CartHandler $cartHandler): Response
    {
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
    public function addToCart(
        int $id,
        ProductRepository $productRepository,
        CartHandler $cartHandler
    ): Response {
        // Récupérer le produit
        $product = $productRepository->find($id);

        if (!$product) {
            $this->addFlash('error', 'Produit non trouvé');
            return $this->redirectToRoute('app_products');
        }

        // Récupérer ou créer le panier
        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);

        if (!$cart) {
            $cart = $cartHandler->createEmptyCart($sessionId);
        }

        // Ajouter le produit au panier (quantité = 1)
        $cartHandler->addItem($cart, $id, 1, $product->getPrix());

        $this->addFlash('success', $product->getNom() . ' ajouté au panier !');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function removeFromCart(int $id, CartHandler $cartHandler): Response
    {
        $sessionId = session_id();
        $cart = $cartHandler->getCart($sessionId);

        if ($cart) {
            $cartHandler->removeItem($cart, $id);
            $this->addFlash('success', 'Produit retiré du panier');
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clearCart(CartHandler $cartHandler): Response
    {
        $cartHandler->clearCart(session_id());
        $this->addFlash('success', 'Panier vidé');
        return $this->redirectToRoute('app_cart');
    }

    // Autres routes...
    #[Route('/products', name: 'app_products')]
    public function products(ProductRepository $productRepository): Response
    {
        return $this->render('product/indew.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_details')]
    public function product(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/product_details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('profile/profile.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->render('security/login.html.twig');
    }
}