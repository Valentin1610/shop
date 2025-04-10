<?php

namespace App\Controller;

use App\Repository\CategoriesProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(Request $request, CategoriesProductsRepository $categoriesProductsRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $categories = $categoriesProductsRepository->findBy([], null, $limit, ($page - 1) * $limit);

        $totalCategories = $categoriesProductsRepository->count([]);
        $totalPages = ceil($totalCategories / $limit);

        return $this->render('categories/index.html.twig', [
            'categories'=> $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
