<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, ThemeRepository $themeRepository, EntityManagerInterface $em): Response
    {

        $page = $request->query->getInt('page', 1);


        $themes = $themeRepository->findThemesPaginated($page);

        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($theme);
            $em->flush();

            $this->addFlash('success', 'Le theme a été créer!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'themes' => $themes,
            'current_page' => $page,
            'total_pages' => ceil(count($themes) / 5),
            'form' => $form->createView(),
        ]);
    }
}
