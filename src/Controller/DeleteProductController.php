<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProductController extends AbstractController
{
    #[Route('/delete_product/{id}', name: 'delete_product')]
    public function index(int $id, EntityManagerInterface $entityManager ): Response
    {

        $product= $entityManager->getRepository(Products::class)->find($id);

        if (!$product) {
            $this->addFlash('danger', 'Le point de vente demandé est introuvable.');
            return $this->redirectToRoute('suppliers');
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('products');
    }
}
