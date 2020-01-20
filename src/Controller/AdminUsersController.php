<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\AuthorSearchByEmailType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\PostService;
use http\Client\Curl\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/admin/users/{id}/view", name="admin_users_view", requirements={"id"="\d+"})
     */
    public function show(\App\Entity\User $user)
    {
        dd(__METHOD__);
    }

    /**
     * @Route("/admin/users/{page}", name="admin_users_list", requirements={"page"="\d+"})
     */
    public function index(UserRepository $repository, PaginatorInterface $paginator, $page = 1)
    {
        $formSearch = $this->createForm(AuthorSearchByEmailType::class);

        $pagination = $paginator->paginate(
            $repository->findBy([], ['id' => 'DESC']),
            $page,
            Post::LIMIT_PER_PAGE
        );

        return $this->render('admin/users/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("admin/user/block/{id}", name="admin_user_block", requirements={"id"="\d+"})
     *
     */
    public function block(\App\Entity\User $user): RedirectResponse
    {
//        /*if ($user->isBanned()) {
//            return $this->fallBackWithError('User is banned already');
//        }
//        $this->service->banUser($user);
//        return $this->redirectBackWithSuccess('User is banned');*/
    }

    /**
     * @Route("admin/user/activate/{id}", name="admin_user_activate", requirements={"id"="\d+"})
     *
     */
    public function activate(\App\Entity\User $user): RedirectResponse
    {
        /*if ($user->isActive()) {
            return $this->fallBackWithError('User is already active');
        }
        $this->service->activateUser($user);
        return $this->redirectBackWithSuccess('User is activated');*/
    }

    public function search(): JsonResponse
    {
        if (is_null($email = $this->request->get('email'))) {
            return $this->json('');
        }
        return $this->json($this->service->search($email));
    }

}
