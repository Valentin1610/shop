<?php

namespace App\Controller;

use App\Entity\PointOfSale;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UpdatePointOfSaleController extends AbstractController
{
    #[Route('/update_point_of_sale/{id}', name: 'update_point_of_sale')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $point_of_sale = $entityManager->getRepository(PointOfSale::class)->find($id);

        if (!$point_of_sale) {
            throw $this->createNotFoundException('Le fournisseur demandée est introuvable');
        }

        if ($request->isMethod('POST')) {
            $enseigne = $request->request->get('enseigne');
            $name = $request->request->get('name');
            $adress = $request->request->get('adress');
            $zipcode = $request->request->get('zipcode');
            $city = $request->request->get('city');

            if (empty($enseigne)) {
                $this->addFlash('danger', 'L\'enseigne du point de vente est obligatoire');
            } 
            if (empty($name)) {
                $this->addFlash('danger', 'Le nom du point de vente est obligatoire.');
            }
            if (empty($adress)) {
                $this->addFlash('danger', 'L\'adresse pour le point de vente est obligatoire.');
            }
            if (empty($zipcode)) {
                $this->addFlash('danger', 'Le code postal pour le point de vente est obligatoire.');
            }
            if (empty($city)) {
                $this->addFlash('danger', 'La ville pour le point de vente est obligatoire.');
            }  else {
                $point_of_sale->setEnseigne($enseigne);
                $point_of_sale->setName($name);
                $point_of_sale->setAdress($adress);
                $point_of_sale->setZipcode($zipcode);
                $point_of_sale->setCity($city);

                $entityManager->persist($point_of_sale);
                $entityManager->flush();

                $this->addFlash('success', 'Point de vente mise à jour avec succès.');

                return $this->redirectToRoute('update_point_of_sale', ['id' => $id]);
            }
        }

        return $this->render('update_point_of_sale/index.html.twig', [
            'point_of_sale' => $point_of_sale,
        ]);
    }
}
