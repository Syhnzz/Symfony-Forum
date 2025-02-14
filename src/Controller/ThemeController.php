<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Theme;
use App\Entity\User;
use App\Form\DiscussionType;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ThemeController extends AbstractController{
    #[Route('/theme/{id}', name: 'app_theme')]
    public function index(Request $request, Theme $theme, DiscussionRepository $discussionRepository,
                          EntityManagerInterface $em, SessionInterface $session): Response
    {

        $page = $request->query->getInt('page', 1);

        $limit = 10;

        $discussions = $discussionRepository->findBy(
            ['theme' => $theme],
            ['createdAt' => 'DESC'],
            $limit,
            ($page - 1) * $limit
        );

        $totalDiscussions = $discussionRepository->count(['theme' => $theme]);
        $totalPages = ceil($totalDiscussions / $limit);
        $discussion = new Discussion();

        $userData = $session->get('user');
        if ($userData) {
            $user = $em->getRepository(User::class)->find($userData['id']);
            $discussion->setUser($user);
        }

        $discussion->setTheme($theme);

        $form = $this->createForm(DiscussionType::class, $discussion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $discussion->setTheme($theme);
            $discussion->setUser($user);
            $discussion->setCreatedAt(new \DateTime());

            $em->persist($discussion);
            $em->flush();

            return $this->redirectToRoute('app_theme', ['id' => $theme->getId()]);
        }

        $editDiscussion = null;
        if ($request->query->get('edit')) {
            $discussionId = $request->query->get('edit');
            $editDiscussion = $discussionRepository->find($discussionId);

            if (!$editDiscussion) {
                throw $this->createNotFoundException('Discussion not found.');
            }

            if (!$this->isGranted('ROLE_MODERATOR') && $editDiscussion->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException('You do not have permission to edit this discussion.');
            }

            $form = $this->createForm(DiscussionType::class, $editDiscussion);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();
                $this->addFlash('success', 'Discussion updated successfully!');
                return $this->redirectToRoute('app_theme', ['id' => $theme->getId()]);
            }
        }

        if ($request->query->get('delete')) {
            $discussionId = $request->query->get('delete');
            $discussion = $discussionRepository->find($discussionId);

            if (!$discussion) {
                throw $this->createNotFoundException('Discussion not found.');
            }

            if (!$this->isGranted('ROLE_MODERATOR') && $discussion->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException('You do not have permission to delete this discussion.');
            }

            $em->remove($discussion);
            $em->flush();
            $this->addFlash('success', 'Discussion supprimer avec succes!');

            return $this->redirectToRoute('app_theme', ['id' => $theme->getId()]);
        }


        return $this->render('theme/index.html.twig', [
            'theme' => $theme,
            'discussions' => $discussions,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'form' => $form->createView(),
            'editDiscussion' => $editDiscussion,
        ]);
    }

}
