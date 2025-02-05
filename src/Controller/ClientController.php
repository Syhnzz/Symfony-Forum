<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClientController
{

    public function index(): Response
    {
        return new Response("Bonjour, nous sommes ouverts !");
    }

}

