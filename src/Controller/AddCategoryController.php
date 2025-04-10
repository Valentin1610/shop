<?php

namespace App\Controller;

use App\Entity\CategoriesProducts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddCategoryController extends AbstractController
{
    #[Route('add_category', name: 'add_category', methods:['POST', 'GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        
        if($request->isMethod('POST')){
            $name = $request->request->get('name');
            $description = $request->request->get('description');

            $category = new CategoriesProducts();
            $category->setName($name);
            $category->setDescription($description);

            $errors = $validator->validate($category);

            if (count($errors) > 0) {
                return $this->render('add_category/index.html.twig', [
                    'errors' => $errors,
                ]);
            }

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès !');
            return $this->redirectToRoute('add_category');
        }

        return $this->render('add_category/index.html.twig'); 
    }
}