<?php

namespace App\Controller;

use App\Entity\CategoriesProducts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdateCategoryController extends AbstractController
{
    #[Route('/update_category/{id}', name: 'update_category')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(CategoriesProducts::class)->find($id);

        if(!$category){
            throw $this->createNotFoundException('La catégorie demandée est introuvable');
        }

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $description = $request->request->get('description');

            if (empty($name)) {
                $this->addFlash('danger', 'Le nom de la catégorie est obligatoire.');
            } else {
                $category->setName($name);
                $category->setDescription($description);

                $entityManager->persist($category);
                $entityManager->flush();

                $this->addFlash('success', 'Catégorie mise à jour avec succès.');

                return $this->redirectToRoute('update_category', ['id' => $id]);
            }
        }
        return $this->render('update_category/index.html.twig',[
            'category' => $category,
        ]);
    }
}
