<?php

namespace App\Controller;

use App\Helpers\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    #[Route('/logout', methods: ['GET'], name: 'logout_page')]
    public function index(Request $request, Session $session): Response
    {
        if (!JWT::isLoggedIn()) {
            $session->set("error", "You're not logged in");
            header('Location: /');
            exit;
        }

        $method = $request->getMethod();

        return $this->render('logout/index.html.twig', []);
    }


    #[Route('/logout', methods: ['POST'], name: 'logout')]
    public function logout(Request $request): Response
    {
        $response = new RedirectResponse($this->generateUrl('home'));
        $response->headers->clearCookie('JWT');
        return $response;
    }
}
