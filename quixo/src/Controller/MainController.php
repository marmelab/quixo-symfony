<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController
{
    /**
     * Return index
     *
     * @param Twig_Environment $twig
     *
     * @return Response
     */
    public function index(\Twig_Environment $twig): Response
    {
        return new Response($twig->render('index.html.twig'));
    }
}
