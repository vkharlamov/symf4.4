<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

//use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @param $page
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function index($page)
    {
        $number = random_int(0, $page);
        $posts = [
            ['id' => 1, 'title' => 'title'],
            ['id' => 2, 'title' => 'title'],
        ];


        return $this->render('main/index.html.twig', [
            'page' => $page,
            'posts' => $posts
        ]);
    }
}
