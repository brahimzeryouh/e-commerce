<?php
use App\Cart\CartHandler;
use App\Cart\SessionCart;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartController extends AbstractController
{
    public function __construct(
        #[Autowire(service: SessionCart::class)]
        private CartHandler $cartHandler
    ) {}

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id): Response
    {
        $this->cartHandler->addToCart($id, 1);
        return $this->redirectToRoute('product_details', ['id' => $id]);
    }
}
