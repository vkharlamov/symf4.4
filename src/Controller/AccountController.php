<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(PostRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $repository->findUserPostOrderedByNewest($this->getUser()->getId()),
            $request->query->getInt('page', 1),
            Post::POST_PER_PAGE
        );

        return $this->render('account/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
