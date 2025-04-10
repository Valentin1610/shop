<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AddUserController extends AbstractController
{
    #[Route('add_user', name: 'users_add_user')]
    public function add_user(Request $request, EntityManagerInterface $entityManager) : Response
    {
        if ($request->isMethod('POST')) {

            $lastname = $request->request->get('lastname');
            $firstname = $request->request->get('firstname');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $role = $request->request->get('role');
            $phone = $request->request->get('phone');

            if (empty($phone)) {
                $phone = null; // Valeur par défaut si le téléphone est vide
            }
            
            $user = new Users();
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));// Hachage du mot de passe
            $user->setRoles([$role]);
            $user->setPhone($phone);

            // Enregistrement dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès !');
            return $this->redirectToRoute('users');
        }
        

        // Affiche le formulaire d'ajout
        return $this->render('add_user/index.html.twig', [
            'controller_name' => 'AddUserController',
        ]);
}
}