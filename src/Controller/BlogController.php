<?php

// src/Controller/BlogController.php
namespace App\Controller;

//use App\Entity\Post;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    public function list()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();


        return $this->render('blog/list.html.twig', ['posts' => $posts]);
    }

    public function show($id)
    {
        return __METHOD__;
//        $post = $this->getDoctrine()
//            ->getRepository(Post::class)
//            ->find($id);
//
//        if (!$post) {
//            // cause the 404 page not found to be displayed
//            throw $this->createNotFoundException();
//        }
//
//        return $this->render('blog/show.html.twig', ['post' => $post]);
    }
}

