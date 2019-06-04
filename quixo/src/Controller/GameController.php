<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;

class GameController
{

    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        $board = Game::getEmptyBoard();

        return new Response($this->twig->render('main.html.twig', [ 'board' => $board ]));
    }
}
