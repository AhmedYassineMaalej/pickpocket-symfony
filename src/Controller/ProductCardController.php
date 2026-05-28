<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Helpers\JWT;
use App\Repository\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductCardController extends AbstractController
{
    #[Route(name: 'product_card')]
    public function renderProductCard(Offer $offer, BookmarkRepository $bookmarkRepository): Response
    {
        $isBookmarked = false;

        if (JWT::isLoggedIn()) {
            $userId = JWT::getUserId();
            $productId = $offer->getProduct()->getId();
            $bookmark = $bookmarkRepository->findBy(['user' => $userId, 'product' => $productId]);

            $isBookmarked = !empty($bookmark);
        }

        return $this->render('product_card/index.html.twig', [
            'offer' => $offer,
            'isBookmarked' => $isBookmarked,
        ]);
    }
}
