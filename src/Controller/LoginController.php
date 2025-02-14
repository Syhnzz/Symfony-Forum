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


    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, SessionInterface $session): Response
    {

        if ($session->has('user')) {
            return $this->redirectToRoute('app_home');
        }

        $failedAttempts = $session->get('failed_attempts', 0);
        $lastFailedAttempt = $session->get('last_failed_attempt', 0);

        if ($failedAttempts >= 3 && (time() - $lastFailedAttempt) < 5) {
            $this->addFlash('error', 'Trop de tentatives échouées. Veuillez patienter 5 secondes.');
            sleep(5);
        }

        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $email = $data->getEmail();
            $password = $data->getPassword();

            $user = $this->userRepository->findByEmail($email);

            if ($user && $this->passwordHasher->isPasswordValid($user, $password)) {

                $session->set('failed_attempts', 0);
                $session->set('last_failed_attempt', 0);

                $session->set('user', [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'username' => $user->getUsername(),
                    'roles' => $user->getRoles(),
                ]);


                return $this->redirectToRoute('app_home');

            } else {
                $failedAttempts++;
                $session->set('failed_attempts', $failedAttempts);
                $session->set('last_failed_attempt', time());

                $this->addFlash('error', 'Identifiants incorrects. Veuillez réessayer.');

                return $this->redirectToRoute('app_login');
            }

        }

        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('user');

        return $this->redirectToRoute('app_home');
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
