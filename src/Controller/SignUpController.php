<?php

namespace App\Controller;

use App\Entity\User;
use App\Helpers\CSRF;
use App\Helpers\JWT;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class SignUpController extends AbstractController
{
    #[Route('/signup', name: 'sign_up')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $method = $request->getMethod();
        $session = $request->getSession();

        if (JWT::isLoggedIn()) {
            $session->set('error', "You're already logged in");
            header('Location: /');
            exit;
        }

        if ($method === 'POST') {
            return self::register($session, $entityManager, $userRepository);
        } elseif ($method === 'GET') {
            $csrf_token = CSRF::generate_token();
            $session->set('csrf_token', $csrf_token);
            error_log("set token: " . $csrf_token);
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            echo "Method Not Allowed";
            exit;
        }

        return $this->render('sign_up/index.html.twig', [
            'csrf_token' => $csrf_token,
        ]);
    }

    public static function register(Session $session, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        // TODO: fix csrf
        /* $csrf_token = $request->get("csrf_token"); */
        /* error_log("read token: " . $csrf_token); */
        /* if (!CSRF::validate_token($csrf_token)) { */
        /*     $session->set('error', 'Invalid security token. Please try again.'); */
        /*     header('Location: /signup'); */
        /*     exit; */
        /* } */

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($password)) {
            $session->set('error', 'Please fill out all the fields');
            header('Location: /signup');
            exit;
        }

        if ($password !== $confirm) {
            $session->set('error', "The passwords don't match");
            header('Location: /signup');
            exit;
        }


        if ($userRepository->findBy(["username" => $username])) {
            $session->set('error', 'User of this name already exists !');
            header('Location: /signup');
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        $user = new User();
        $user->setPassword($hashedPassword);
        $user->setUsername($username);
        $entityManager->persist($user);
        $entityManager->flush();

        if ($user) {
            $user_id = $user->getId();
            $jwt = JWT::issue_jwt($username, $user_id);
            /*
            below i set the argument "secure" of setcookie to false, because if i dont
            then http://localhost will not accept that token because its not https
            */
            setcookie('JWT', $jwt, time() + 3600, '/', '', false, true);

            header('Location: /?success=account_created');
            exit;
        } else {
            $_SESSION['error'] = 'DB ERROR';
            header('Location: /signup');
            exit;
        }
    }

    public static function show_signup_form() {}
}
