<?php

namespace App\Controller;

use App\Entity\User;
use App\Helpers\JWT;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SignUpController extends AbstractController
{
    #[Route('/signup', methods: ["GET"], name: 'sign_up_page')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $method = $request->getMethod();
        $session = $request->getSession();

        if (JWT::isLoggedIn()) {
            $session->set('error', "You're already logged in");
            header('Location: /');
            exit;
        }

        return $this->render('sign_up/index.html.twig', []);
    }


    #[Route('/signup', methods: ["POST"], name: 'sign_up')]
    public static function signup(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $session = $request->getSession();
        $params = $request->request;

        $username = $params->get("username");
        $password = $params->get("password");
        $confirm  = $params->get("confirm_password");

        $response = new RedirectResponse("/signup");

        if (empty($username) || empty($password)) {
            $session->set('error', 'Please fill out all the fields');
            return $response;
        }

        if ($password !== $confirm) {
            $session->set('error', "The passwords don't match");
            return $response;
        }


        if ($userRepository->findBy(["username" => $username])) {
            $session->set('error', 'User of this name already exists !');
            return $response;
        }

        $user = new User();
        $user->setPassword($password);
        $user->setUsername($username);
        $entityManager->persist($user);
        $entityManager->flush();

        $user_id = $user->getId();
        $jwt = JWT::issue_jwt($username, $user_id);
        $cookie = new Cookie('JWT', $jwt, time() + 3600, '/', '', false, true);
        $response->headers->setCookie($cookie);
        $response->setTargetUrl("/");
        return $response;
    }
}
