<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailController extends AbstractController
{
    #[Route('/product/{id}', name: 'product_detail', requirements: ['id' => '\d+'])]
    public function index(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found.');
        }

        $offers = $product->getOffers()->toArray();
        usort($offers, fn($a, $b) => $a->getPrice() <=> $b->getPrice());

        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'offers'  => $offers,
            'category' => $product->getCategory(),
        ]);
    }
}
