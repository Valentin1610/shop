<?php

namespace App\Controller;

use App\Entity\PointOfSale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeletePointOfSaleController extends AbstractController
{
    #[Route('/delete_point_of_sale/{id}', name: 'delete_point_of_sale', methods: ['POST', 'GET'])]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {

        $point_of_sale = $entityManager->getRepository(PointOfSale::class)->find($id);

        if (!$point_of_sale) {
            $this->addFlash('danger', 'Le point de vente demandé est introuvable.');
            return $this->redirectToRoute('suppliers');
        }

        $entityManager->remove($point_of_sale);
        $entityManager->flush();

        return $this->redirectToRoute('point_of_sales');
    }
}
