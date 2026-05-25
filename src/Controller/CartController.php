<?php
namespace App\Controller;

use App\Cart\CartHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(
        private CartHandler $cartHandler
    ) {}

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id): Response
    {
        $this->cartHandler->addToCart($id, 1);
        //return $this->redirectToRoute('product_details', ['id' => $id]);
        return $this->redirectToRoute('app_home');

    }
}
