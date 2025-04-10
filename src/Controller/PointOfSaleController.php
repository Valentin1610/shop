<?php

namespace App\Controller;

use App\Repository\PointOfSaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PointOfSaleController extends AbstractController
{
    #[Route('/point_of_sale', name: 'point_of_sales')]
    public function index(Request $request, PointOfSaleRepository $pointOfSaleRepository): Response
    {

        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $point_of_sales = $pointOfSaleRepository->findBy([], null, $limit, ($page - 1) * $limit);

        $totalpoint_of_sales = $pointOfSaleRepository->count([]);
        $totalPages = ceil($totalpoint_of_sales / $limit);


        return $this->render('point_of_sale/index.html.twig', [
            'point_of_sales' => $point_of_sales,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
