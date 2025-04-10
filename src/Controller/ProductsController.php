<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function products(Request $request, ProductsRepository $productsRepository): Response
    {
        $page = $request->query->getInt('page',1);
        $limit = 10;
        $products = $productsRepository->findBy([], null, $limit, ($page - 1) * $limit);

        $totalProducts = $productsRepository->count([]);
        $totalPages = ceil($totalProducts / $limit);

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
}
