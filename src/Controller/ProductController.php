<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', methods: ["GET"], name: 'product', requirements: ['id' => '\d+'])]
    public function index(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Product not found.');
        }

        $offers = $product->getOffers()->toArray();
        usort($offers, fn($a, $b) => $a->getPrice() <=> $b->getPrice());

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'offers'  => $offers,
            'category' => $product->getCategory(),
        ]);
    }
}
