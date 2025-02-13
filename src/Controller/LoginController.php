<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;

class LoginController extends AbstractController
{
    private $userRepository;
    private $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }


    #[Route(path: '/login', name: 'app_new_login')]
    public function login(Request $request, SessionInterface $session): Response
    {

        if ($session->has('user')) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $email = $data->getEmail();
            $password = $data->getPassword();

            $user = $this->userRepository->findOneByEmail($email);

            /*

            $sessionLogin = $session->get('login');
            $sessionPassword = $session->get('password');

            if ($login === $sessionLogin && $password === $sessionPassword) {
                return $this->redirectToRoute('dashboard');
            }

            */
            if ($form->isSubmitted() && $user && $this->passwordHasher->isPasswordValid($user, $password)) {

                    $session->set('user', [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'roles' => $user->getRoles(),
                    ]);


                    return $this->redirectToRoute('home');

                } elseif($form->isSubmitted() && !$form->isValid()){
                $this->addFlash('error', 'Vous n\'avez pas bien rempli le formulaire.');
            }

        }


        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_new_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('user');

        return $this->redirectToRoute('home');
    }

    private function isAuthenticated(SessionInterface $session): bool
    {
        return $session->has('user');
    }

    private function hasRole(SessionInterface $session, string $role): bool
    {
        $user = $session->get('user');
        return in_array($role, $user['roles']);
    }
}


