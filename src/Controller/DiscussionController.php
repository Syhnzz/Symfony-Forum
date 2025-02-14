<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Discussion;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\DiscussionType;
use App\Repository\CommentRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class DiscussionController extends AbstractController{

    #[Route('/discussion/{id}', name: 'app_discussion')]
    public function discussion(
        Discussion $discussion,
        Request $request,
        EntityManagerInterface $em,
        CommentRepository $commentRepository,
        SessionInterface $session
    ): Response {

        $comments = $commentRepository->findBy(['discussion' => $discussion], ['createdAt' => 'ASC']);

        $comment = new Comment();

        $userData = $session->get('user');
        if ($userData) {
            $user = $em->getRepository(User::class)->find($userData['id']);
            $comment->setUser($user);
        }


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setDiscussion($discussion);
            $comment->setCreatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_discussion', ['id' => $discussion->getId()]);
        }

        return $this->render('discussion/index.html.twig', [
            'discussion' => $discussion,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
