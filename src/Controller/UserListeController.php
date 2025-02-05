<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserListeController extends AbstractController
{
    #[Route('/user/liste', name: 'app_user_liste')]
    public function list(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();

        return $this->render('user_liste/index.html.twig', [
            'users' => $users,
        ]);
    }
}
