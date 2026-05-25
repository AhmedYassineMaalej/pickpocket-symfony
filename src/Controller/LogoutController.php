<?php

namespace App\Controller;

use App\Helpers\CSRF;
use App\Helpers\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;

final class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function index(Request $request, Session $session): Response
    {
        if (!JWT::isLoggedIn()) {
            $session->set("error", "You're already logged in");
            header('Location: /');
            exit;
        }

        $method = $request->getMethod();

        if ($method === 'POST') {
            return self::logout();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $csrf_token = CSRF::generate_token();
        } else {
            header('HTTP/1.1 405 Method Not Allowed');
            echo "Method Not Allowed";
            exit;
        }
        return $this->render('logout/index.html.twig', []);
    }


    public static function logout()
    {
        $csrf_token = $_POST['csrf'];
        if (!CSRF::validate_token($csrf_token)) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
            header('Location: /');
            exit;
        }

        setcookie("JWT", "", time() - 3600, "/", "", false, true);
        $_SESSION['csrf_token'] = null;
        unset($_COOKIE["JWT"]);
        header('Location: /');
        exit;
    }
}
