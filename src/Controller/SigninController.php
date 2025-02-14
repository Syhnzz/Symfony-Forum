<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class SigninController extends AbstractController{
    #[Route('/signin', name: 'app_signin')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(SigninType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $password = $data->getPassword();
            $confirmPassword = $form->get('confirm_password')->getData();

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'The passwords do not match.');
                return $this->render('signin/index.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $encodedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($encodedPassword);

            $roles = ['ROLE_USER'];

            $user->setRoles($roles);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('signin/index.html.twig', [
            'form' => $form,
        ]);
    }
}
