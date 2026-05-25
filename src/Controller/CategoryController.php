<?php
namespace App\Controller;

use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryServiceInterface $categoryService)
    {
    }

    #[Route('/categories', name: 'app_categories')]
    public function list(): Response
    {
        return $this->render('category/browse_categories.html.twig', [
            'categories' => $this->categoryService->getAllCategories(),
        ]);
    }
}