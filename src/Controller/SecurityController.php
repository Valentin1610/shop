<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'security_login')]
    public function security_login(AuthenticationUtils $authenticationUtils)
    {
        // // if ($this->getUser()) {
        // //     return $this->redirectToRoute('target_path');
        // // }

        // // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',
        ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function security_logout(){ }
}
