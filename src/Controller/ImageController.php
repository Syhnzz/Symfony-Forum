<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    function home() : Response{
        return $this->render('img/home.html.twig');
    }

    public function affiche(string $fichier): Response
    {

        $image = $this->getParameter('kernel.project_dir') . '/public/images/' . $fichier;


        if (!file_exists($image)) {
            throw $this->createNotFoundException('Fichier non trouver');
        }

        $imageData = file_get_contents($image);
        $mimeType = mime_content_type($image);
        $response = new Response($imageData);

        $response->headers->set('Content-Type', $mimeType);
        $response->headers->set('Content-Length', strlen($imageData));

        return $response;
    }

    public function menu(): Response
    {

        $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/images/';

        if (!is_dir($imageDirectory)) {
            throw $this->createNotFoundException('Repertoire non trouver');
        }

        $files = scandir($imageDirectory);

        $images = array_filter($files, function ($file) use ($imageDirectory) {
            return !is_dir($imageDirectory . $file);
        });

        return $this->render('menu/menu.html.twig', [
            'images' => $images,
        ]);

        return $this->file($imageDirectory, $images);
    }


}