<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Routing\Attribute\Route;

class Client extends AbstractController
{
    //#[Route('/client/prenom/{prenom}', name: 'info', requirements: ['prenom' => '[a-zA-Z]+\w+(-\w+)*']
    //    , methods: ['GET'])]
    public function info($prenom): Response
    {
        $maReponse = new Response( 'prenom : ' . $prenom );
        return $maReponse;
    }

}