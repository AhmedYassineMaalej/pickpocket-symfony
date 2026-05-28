<?php

namespace App\Controller;

use App\Helpers\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NavbarController extends AbstractController
{
    #[Route(name: 'navbar')]
    public function renderNavbar(): Response
    {
        return $this->render('navbar/index.html.twig', [
            'isLoggedIn' => JWT::isLoggedIn(),
        ]);
    }
}
