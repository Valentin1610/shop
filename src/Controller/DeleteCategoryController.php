<?php

namespace App\Controller;

use App\Entity\CategoriesProducts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteCategoryController extends AbstractController
{
    #[Route('/delete_category/{id}', name: 'delete_category', methods: ['POST','GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(CategoriesProducts::class)->find($id);

        if (!$category) {
            $this->addFlash('danger', 'La catégorie demandée est introuvable.');
            return $this->redirectToRoute('categories');
        }
        
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('categories');
    }
}

