<?php
namespace App\Controller;

use App\Service\CategoryServiceInterface;
use App\Service\ProductServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductServiceInterface $productService,
        private CategoryServiceInterface $categoryService
    ) {
    }

    #[Route('/products', name: 'app_products')]
    public function list(PaginatorInterface $paginator, Request $request): Response
    {
        $products = $paginator->paginate(
            $this->productService->getAllProducts(),
            $request->query->getInt('page', 1),
            4 // 9 produits par page
        );

        return $this->render('product/indew.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_details')]
    public function show(int $id): Response
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/product_details.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/category/{slug}', name: 'app_products_by_category')]
    public function byCategory(string $slug, PaginatorInterface $paginator, Request $request): Response
    {
        $category = $this->categoryService->getCategoryBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $products = $paginator->paginate(
            $this->productService->getProductsByCategorySlug($slug),
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('product/products_by_category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}