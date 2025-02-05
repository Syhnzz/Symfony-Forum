<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;

class ImageController extends AbstractController
{
    function home() : Response{
        return $this->render('img/home.html.twig');
    }

    public function affiche(string $fichier): Response
    {

        $image = $this->getParameter('kernel.project_dir') . '/public/images/';

        $possibleExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        $imagePath = null;
        foreach ($possibleExtensions as $ext) {
            $path = $image . '/' . $fichier . '.' . $ext;
            if (file_exists($path)) {
                $imagePath = $path;
                break;
            }
        }

        if (!$imagePath) {
            throw new NotFoundHttpException('Image non trouvée.');
        }

        $file = new File($imagePath);
        $mimeType = $file->getMimeType();

        $imageContent = file_get_contents($imagePath);

        return new Response(
            $imageContent,
            200,
            ['Content-Type' => $mimeType]
        );
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

    }


}