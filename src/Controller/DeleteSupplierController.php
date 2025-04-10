<?php

namespace App\Controller;

use App\Entity\Suppliers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteSupplierController extends AbstractController
{
    #[Route('/delete_supplier/{id}', name: 'delete_supplier',methods: ['POST', 'GET'] )]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {
        $supplier = $entityManager->getRepository(Suppliers::class)->find($id);

        if (!$supplier) {
            $this->addFlash('danger', 'Le fournisseur demandée est introuvable.');
            return $this->redirectToRoute('suppliers');
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        return $this->redirectToRoute('suppliers');
    }
}
