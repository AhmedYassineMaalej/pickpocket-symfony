<?php

namespace App\Controller;

use App\Helpers\JWT;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    private UserRepository $userRepository;
    #[Route('/login', methods: ["GET"], name: 'login_page')]
    public function index(Request $request, Session $session, UserRepository $userRepository): Response
    {
        $session = $request->getSession();

        if (JWT::isLoggedIn()) {
            $session->set("error", "You're already logged in !");
            header('Location: /');
            exit;
        }

        return $this->render('login/index.html.twig', []);
    }


    #[Route('/login', methods: ["POST"], name: 'login')]
    public function login(Request $request, UserRepository $userRepository): Response
    {
        $session = $request->getSession();
        $response = new RedirectResponse("/login");

        $username = $request->request->get("username");
        $password = $request->request->get("password");

        if (empty($username) || empty($password)) {
            $session->set('error', 'Please fill out all the fields');
            return $response;
        }

        $user = $userRepository->findBy(["username" => $username])[0];

        if ($user && password_verify($password, $user->getPassword())) {
            $jwt_cookie = JWT::issue_jwt($username, $user->getId());
            $cookie = new Cookie("JWT", $jwt_cookie, time() + 3600, "/", secure: false, httpOnly: true);
            $response->headers->setCookie($cookie);
            $response->setTargetUrl('/');
            return $response;
        }

        $session->set('error', 'Invalid credentials');
        return $response;
    }
}
