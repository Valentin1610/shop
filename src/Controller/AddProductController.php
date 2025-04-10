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

class AddProductController extends AbstractController
{
    #[Route('/add_product', name: 'add_products', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
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

            $image = $request->files->get('image');
            if ($image) {
                $imageName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $imageName);
            } else {
                $this->addFlash('error', 'L’image est requise.');
            }

            // Création de l'entité Product
            $product = new Products();
            $product->setName($name);
            $product->setReference($reference);
            $product->setDescription($description);
            $product->setPrice($price);
            $product->setEmplacementRayonnage($emplacement);
            $product->setStock($stock);
            $product->setCategory($category); // Catégorie récupérée
            $product->setSupplier($supplier); // Fournisseur récupéré

            // Sauvegarde en base de données
            $entityManager->persist($product);
            try {
                $this->addFlash('success', 'Produit ajouté avec succés !!');
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l’enregistrement du produit.');
            }

            // Redirection ou message de succès
            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('add_products');
        }

        return $this->render('add_product/index.html.twig', [
            'categories' =>$entityManager->getRepository(CategoriesProducts::class)->findAll(),
            'suppliers' =>$entityManager->getRepository(Suppliers::class)->findAll(),
        ]);
    }
}