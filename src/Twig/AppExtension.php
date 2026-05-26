<?php

namespace App\Twig;

use App\Helpers\JWT;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function getGlobals(): array
    {
        return [
            'categories' => $this->categoryRepository->findBy([], ['name' => 'ASC']),
            "isLoggedIn" => JWT::isLoggedIn(),

        ];
    }
}
