<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Products;
use App\Entity\CategoriesProducts;
use App\Entity\Suppliers;
use Doctrine\ORM\EntityManagerInterface;

class UpdateProductController extends AbstractController
{
    #[Route('/update_product/{id}', name: 'update_product')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Le produit demandé est introuvable');
        }

        if ($request->isMethod('POST')) {

            $name = $request->request->get('name');
            $reference = $request->request->get('reference');
            $description = $request->request->get('description');
            $price = $request->request->get('price');
            $emplacement = $request->request->get('emplacement_rayonnage');
            $stock = $request->request->get('stock');
            $categoryId = $request->request->get('category');
            $supplierId = $request->request->get('supplier');

            $category = $entityManager->getRepository(CategoriesProducts::class)->find($categoryId);
            $supplier = $entityManager->getRepository(Suppliers::class)->find($supplierId); 

            if (empty($name)) {
                $this->addFlash('danger', 'Le nom du produit est obligatoire.');
            }
            if (empty($reference)) {
                $this->addFlash('danger', 'La référence du produit est obligatoire');
            }
            if (empty($description)) {
                $this->addFlash('danger', 'La description du porduit est obligatoire.');
            }
            if (empty($price)) {
                $this->addFlash('danger', 'Le prix du produit est obligatoire.');
            }
            if (empty($emplacement)) {
                $this->addFlash('danger', 'L\'emplacement du produit est obligatoire.');
            }
            if (empty($stock)) {
                $this->addFlash('danger', 'Le stock du produit est obligatoire.');
            }
            if (empty($categoryId)) {
                $this->addFlash('danger', 'Le catégorie du produit est obligatoire.');
            }
            if (empty($supplierId)) {
                $this->addFlash('danger', 'Le fournisseur est obligatoire.');
            } else {
                $product->setName($name);
                $product->setReference($reference);
                $product->setDescription($description);
                $product->setPrice($price);
                $product->setEmplacementRayonnage($emplacement);
                $product->setStock($stock);
                $product->setCategory($category); 
                $product->setSupplier($supplier); 

                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash('success', 'Produit mise à jour avec succès.');

                return $this->redirectToRoute('update_product', ['id' => $id]);
            }
        }
        return $this->render('update_product/index.html.twig', [
            'product' => $product,
            'categories' => $entityManager->getRepository(CategoriesProducts::class)->findAll(),
            'suppliers' => $entityManager->getRepository(Suppliers::class)->findAll(),
        ]);
    }
}
