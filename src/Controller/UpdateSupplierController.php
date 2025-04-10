<?php

namespace App\Controller;

use App\Entity\Suppliers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdateSupplierController extends AbstractController
{
    #[Route('/update_supplier/{id}', name: 'update_supplier')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplier = $entityManager->getRepository(Suppliers::class)->find($id);


        if (!$supplier) {
            throw $this->createNotFoundException('Le fournisseur demandée est introuvable');
        }

        if ($request->isMethod('POST')) {
            $denomination = $request->request->get('denomination');
            $name = $request->request->get('name');
            $reference = $request->request->get('reference');
            $phone = $request->request->get('phone');
            $email = $request->request->get('email');
            
            if (empty($name)) {
                $this->addFlash('danger', 'Le nom du fournisseur est obligatoire.');
            } else {
                $supplier->setDenomination($denomination);
                $supplier->setName($name);
                $supplier->setReference($reference);
                $supplier->setPhone($phone);
                $supplier->setEmail($email);

                $entityManager->persist($supplier);
                $entityManager->flush();

                $this->addFlash('success', 'Fournisseur mise à jour avec succès.');

                return $this->redirectToRoute('update_supplier', ['id' => $id]);
            }
        }

        return $this->render('update_supplier/index.html.twig', [
            'supplier' => $supplier,
        ]);
    }
}
