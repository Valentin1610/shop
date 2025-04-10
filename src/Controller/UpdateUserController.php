<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UpdateUserController extends AbstractController
{
    #[Route('/users_update_user/{id}', name: 'update_user')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id);

        if(!$user){
            throw $this->createNotFoundException('L\'utilisateur demandé est introuvable');
        }

        if($request->isMethod('POST')){

            $lastname = $request->request->get('lastname');
            $firstname = $request->request->get('firstname');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $role = $request->request->get('role');
            $phone = $request->request->get('phone');
        }

        if(empty($lastname)){
            $this->addFlash('danger', 'Le nom de famille de l\'utilisateur est obligatoire.');
        } else{
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setEmail($email);
            if (!empty($password)) {
                $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); // Hachage du mot de passe
            }
            $user->setRoles([$role]);
            $user->setPhone($phone);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été mise à jour avec succès.');

        }
        return $this->render('update_user/index.html.twig', [
            'user' => $user
        ]);
    }
}