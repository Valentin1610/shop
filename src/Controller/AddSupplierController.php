<?php

namespace App\Controller;

use App\Entity\Suppliers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddSupplierController extends AbstractController
{
    #[Route('/add_supplier', name: 'add_supplier', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        if($request->isMethod('POST')){
            
            $denomination = $request->request->get('denomination');
            $name = $request->request->get('name');
            $reference = $request->request->get('reference');
            $phone = $request->request->get('phone');
            $email =$request->request->get('email');

            $supplier = new Suppliers();
            $supplier->setDenomination($denomination);
            $supplier->setName($name);
            $supplier->setReference($reference);
            $supplier->setPhone($phone);
            $supplier->setEmail($email);

            $errors = $validator->validate($supplier);

            if (count($errors) > 0) {
                return $this->render('add_supplier/index.html.twig', [
                    'errors' => $errors,
                ]);
            }

            $entityManager->persist($supplier);
            $entityManager->flush();

            $this->addFlash('success', 'Fournisseur ajouté avec succés !');
            return $this->redirectToRoute('add_supplier');
        }
        return $this->render('add_supplier/index.html.twig');
    }
}