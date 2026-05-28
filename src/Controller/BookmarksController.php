<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Helpers\JWT;
use App\Repository\BookmarkRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookmarksController extends AbstractController
{
    #[Route('/bookmarks/add', name: 'add_bookmark')]
    public function addBookmark(
        Request $request,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        if (!JWT::isLoggedIn()) {
            return new JsonResponse(['error' => 'Not Authorized'], Response::HTTP_UNAUTHORIZED);
        }

        $userId = JWT::getUserId();
        $user = $userRepository->find($userId);

        $productId = $request->request->get("productId");
        $product = $productRepository->find($productId);


        $bookmark = new Bookmark();
        $bookmark->setUser($user);
        $bookmark->setProduct($product);

        $entityManager->persist($bookmark);
        $entityManager->flush();

        return new Response('OK');
    }

    #[Route('/bookmarks/remove', name: 'remove_bookmark')]
    public function removeBookmark(
        Request $request,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager,
        BookmarkRepository $bookmarkRepository,
    ): Response {
        if (!JWT::isLoggedIn()) {
            return new JsonResponse(['error' => 'Not Authorized'], Response::HTTP_UNAUTHORIZED);
        }


        $userId = JWT::getUserId();
        $productId = $request->request->get("productId");

        $bookmark = $bookmarkRepository->findBy(['user' => $userId, 'product' => $productId]);

        if (empty($bookmark)) {
            throw $this->createNotFoundException('The bookmark does not exist');
        }

        $entityManager->remove($bookmark[0]);
        $entityManager->flush();

        return new Response('OK');
    }
}
