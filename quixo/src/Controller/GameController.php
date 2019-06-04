<?php
// src/Controller/GameController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class GameController
{
    public function index()
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}
