<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\PostService;
use http\Client\Curl\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * Class AdminUsersController
 * @package App\Controller
 *
 *
 */
class AdminUsersController extends AbstractController
{
    /**
     * @Route("/admin/users/{page}", name="admin_users_list", requirements={"page"="\d+"})
     */
    public function index(PostRepository $repository, PaginatorInterface $paginator, $page = 1)
    {
        $pagination = $paginator->paginate(
            $repository->getPostListOrderedByNewest(),
            $page,
            Post::LIMIT_PER_PAGE
        );

        return $this->render('admin/users/index.html.twig', [
            'pagination' => $pagination
        ]);

    }

    /**
     * @Route("/admin/users/{id}/view", name="admin_users_view", requirements={"id"="\d+"})
     */
    public function show(\App\Entity\User $user)
    {
        dd(__METHOD__);
    }

}
