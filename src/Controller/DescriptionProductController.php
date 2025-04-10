<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DescriptionProductController extends AbstractController
{
    #[Route('/description_product/{id}', name: 'description_product')]
    public function index(int $id, ProductsRepository $productsRepository): Response
    {
        $descriptionProduct = $productsRepository->find($id);

        if (! $descriptionProduct) {
            throw $this->createNotFoundException("Produit introuvable.");
        }
        return $this->render('description_product/index.html.twig', [
            'descriptionProduct' => $descriptionProduct
        ]);
    }
}
