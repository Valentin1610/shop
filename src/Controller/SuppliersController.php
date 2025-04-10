<?php

namespace App\Controller;

use App\Repository\SuppliersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SuppliersController extends AbstractController
{
    #[Route('/suppliers', name: 'suppliers')]
    public function index(Request $request, SuppliersRepository $suppliersRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $categories = $suppliersRepository->findBy([], null, $limit, ($page - 1) * $limit);

        $totalCategories = $suppliersRepository->count([]);
        $totalPages = ceil($totalCategories / $limit);

        return $this->render('suppliers/index.html.twig', [
            'suppliers' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
