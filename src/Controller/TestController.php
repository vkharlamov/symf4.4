<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     *
     */
    public function index($number)
    {
        $number = random_int(0, $number);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}
