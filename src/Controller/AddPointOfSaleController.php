<?php

namespace App\Controller;

use App\Entity\PointOfSale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddPointOfSaleController extends AbstractController
{
    #[Route('/add_point_of_sale', name: 'add_point_of_sale', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        if ($request->isMethod('POST')) {
            $enseigne = $request->request->get('enseigne');
            $name = $request->request->get('name');
            $adress = $request->request->get('adress');
            $zipcode = $request->request->get('zipcode');
            $city = $request->request->get('city');

            $point_of_sale = new PointOfSale();
            $point_of_sale->setEnseigne($enseigne);
            $point_of_sale->setName($name);
            $point_of_sale->setAdress($adress);
            $point_of_sale->setZipcode($zipcode);
            $point_of_sale->setCity($city);

            $errors = $validator->validate($point_of_sale);

            if (count($errors) > 0) {
                return $this->render('add_point_of_sale/index.html.twig', [
                    'errors' => $errors,
                ]);
            }

            $entityManager->persist($point_of_sale);
            $entityManager->flush();

            $this->addFlash('success', 'Fournisseur ajouté avec succés !');
            return $this->redirectToRoute('add_point_of_sale');
        }
        return $this->render('add_point_of_sale/index.html.twig');
    }
}