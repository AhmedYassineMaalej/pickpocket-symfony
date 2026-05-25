<?php

namespace App\Controller;

use App\Helpers\CSRF;
use App\Helpers\JWT;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    private UserRepository $userRepository;
    #[Route('/login', name: 'login')]
    public function index(Request $request, Session $session, UserRepository $userRepository): Response
    {
        if (JWT::isLoggedIn()) {
            $_SESSION['error'] = "You're already logged in !";
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return self::authenticate($userRepository);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $token = CSRF::generate_token();
            $session->set("csrf_token", $token);
            return $this->render('login/index.html.twig', [
                'controller_name' => 'LoginController',
            ]);
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            echo "Method Not Allowed";
            exit;
        }


        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    public static function authenticate(UserRepository $userRepository)
    {

        // validate that CSRF Token if it exists ofc
        $csrf_token = $_POST['csrf'] ?? '';
        if (! CSRF::validate_token($csrf_token ?? '')) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
            header('Location: /login');
            exit;
        }
        // get username and password from POST data

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Please fill out all the fields';
            header('Location: /login');
            exit;
        }

        $user = $userRepository->findBy(["username" => $username])[0];

        if ($user && password_verify($password, $user->getPassword())) {
            $jwt_cookie = JWT::issue_jwt($username, $user->getId());
            setcookie("JWT", $jwt_cookie, time() + 3600, "/", "", false, true);
            error_log("JWT issued: " . $jwt_cookie);
            header('Location: /myspace');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: /login');
            exit;
        }
    }

    public static function show_login_form() {}
}
