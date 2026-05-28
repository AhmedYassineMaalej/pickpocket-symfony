<?php

namespace App\Controller;

use App\Helpers\JWT;
use App\Repository\RecommendationRepository;
use App\Repository\UserRepository;
use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Attribute\Route;

class MySpaceController extends AbstractController
{
    #[Route('/myspace', name: 'app_myspace', methods: ['GET'])]
    public function index(
        Request $request, 
        RecommendationRepository $recommendationRepo, 
        BookmarkRepository $bookmarkRepo,
        UserRepository $userRepo
    ): Response {
        if (!JWT::isLoggedIn()) {
            $this->addFlash('error', "You're not logged in");
            return $this->redirectToRoute('login_page'); 
        }

        $jwtCookie = $request->cookies->get('JWT');
        $payload = JWT::verify_jwt($jwtCookie); 
        $sessionUsername = (is_array($payload) && isset($payload['user'])) ? $payload['user'] : ''; 

        $user = $userRepo->findOneBy(['username' => $sessionUsername]);

        if (!$user) {
            $this->addFlash('error', "User profile data record could not be resolved.");
            return $this->redirectToRoute('login_page');
        }

        $currentTab = $request->query->get('tab', 'dashboard');
        $recommendedProducts = [];
        $bookmarks = [];

        if ($currentTab === 'dashboard') {
            $recommendedProducts = $recommendationRepo->findBy(['user' => $user], null, 6);
        } elseif ($currentTab === 'bookmarks') {
            $bookmarks = $bookmarkRepo->findBy(['user' => $user]);
        }

        return $this->render('myspace/index.html.twig', [
            'currentTab'          => $currentTab,
            'username'            => $user->getUsername(),
            'recommendedProducts' => $recommendedProducts,
            'bookmarks'           => $bookmarks,
        ]);
    }

    #[Route('/myspace/update', name: 'app_myspace_update', methods: ['POST'])]
    public function updateProfile(
        Request $request, 
        UserRepository $userRepo, 
        EntityManagerInterface $entityManager
    ): Response {
        if (!JWT::isLoggedIn()) {
            return $this->redirectToRoute('login_page');
        }

        $jwtCookie = $request->cookies->get('JWT');
        $payload = JWT::verify_jwt($jwtCookie);
        $sessionUsername = (is_array($payload) && isset($payload['user'])) ? $payload['user'] : '';

        $user = $userRepo->findOneBy(['username' => $sessionUsername]);
        if (!$user) {
            return $this->redirectToRoute('login_page');
        }

        $newUsername = trim($request->request->get('username', ''));
        $oldPassword = $request->request->get('old_password', '');
        $newPassword = $request->request->get('new_password', '');

        if (empty($newUsername) || empty($oldPassword) || empty($newPassword)) {
            $this->addFlash('error', 'All fields are required.');
            return $this->redirectToRoute('app_myspace', ['tab' => 'settings']);
        }

        if (!password_verify($oldPassword, $user->getPassword())) {
            $this->addFlash('error', 'Incorrect current password.');
            return $this->redirectToRoute('app_myspace', ['tab' => 'settings']);
        }

        try {
            $user->setUsername($newUsername);
            $user->setPassword($newPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $newToken = JWT::issue_jwt($user->getUsername(), $user->getId());
            
            $response = $this->redirectToRoute('app_myspace', ['tab' => 'settings']);
            $response->headers->setCookie(new Cookie('JWT', $newToken, time() + 3600, '/'));

            $this->addFlash('success', 'Profile updated successfully!');
            return $response;

        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to update profile: ' . $e->getMessage());
            return $this->redirectToRoute('app_myspace', ['tab' => 'settings']);
        }
    }
}