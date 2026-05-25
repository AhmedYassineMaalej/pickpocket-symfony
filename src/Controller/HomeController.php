<?php

namespace App\Controller;

use App\Helpers\JWT;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(OfferRepository $offerRepository): Response
    {
        $bestDeals = $offerRepository->findBestDeals();
        $newestDeals = $offerRepository->findNewestDeals();
        $expiringDeals = $offerRepository->findExpiringDeals();
        return $this->render('home/index.html.twig', [
            "isLoggedIn" => JWT::isLoggedIn(),
            "bestDeals" => $bestDeals,
            "newestDeals" => $newestDeals,
            "expiringDeals" => $expiringDeals,
            "offerOfTheDay" => $bestDeals[0],
        ]);
    }
}
