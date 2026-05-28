<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(
        Request $request,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $query      = $request->query->get('q', '');
        $categoryId = $request->query->get('category');
        $minPrice   = $request->query->get('minPrice') !== null ? (float) $request->query->get('minPrice') : null;
        $maxPrice   = $request->query->get('maxPrice') !== null ? (float) $request->query->get('maxPrice') : null;

        $products = $productRepository->searchProducts($query, $categoryId, $minPrice, $maxPrice);

        $currentCategory = $categoryId
            ? $categoryRepository->find($categoryId)
            : null;

        return $this->render('search/index.html.twig', [
            'products'        => $products,
            'query'           => $query,
            'currentCategory' => $currentCategory,
        ]);
    }
}
