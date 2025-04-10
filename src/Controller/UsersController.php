<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UsersRepository;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'users')]
    public function index(Request $request, UsersRepository $userRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $users = $userRepository->findBy([], null, $limit, ($page - 1 )* $limit);

        $totalUsers = $userRepository->count([]);
        $totalPages = ceil($totalUsers / $limit);

        return $this->render('users/index.html.twig', [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}