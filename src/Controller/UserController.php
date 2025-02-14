<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, SessionInterface $session): Response
    {

        if (!$session->has('user')) {
            return $this->redirectToRoute('app_login');
        }

        $userData = $session->get('user');
        $user = $this->entityManager->getRepository(User::class)->find($userData['id']);

        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            $session->set('user', [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]);

            $this->addFlash('success', 'Profile mis a jour');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateurs', name: 'app_users_list')]
    public function listUsers(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
