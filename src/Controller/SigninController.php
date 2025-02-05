<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SigninController extends AbstractController
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/img/home/signin', name: 'app_signin')]
    public function index(Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher,
                          //SessionInterface $session
    ): Response
    {
        $user = new User();

        $form = $this->createForm(SigninType::class, $user);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $login = $user->getEmail();

            $password = $form->get('password')->getData();

            $confirmPassword = $form->get('confirm_password')->getData();

            if ($password !== $confirmPassword) {
                $this->addFlash('error', $this->translator->trans('The passwords do not match.'));
                return $this->redirectToRoute('app_signin');
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            /*

            $session->set('login', $login);
            $session->set('password', $password);

            */

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('registration_success', ['login' => $login]);

        }elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', $this->translator->trans('The form was not submited correctly'));

            return $this->redirectToRoute('app_signin');
        }

        return $this->render('signin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function registerSuccess($login): Response{
        return $this->render('signin/success.html.twig', [
            'login' => $login,
        ]);
    }
}
