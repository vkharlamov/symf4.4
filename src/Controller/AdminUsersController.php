<?php

namespace App\Controller;

use App\Controller\BaseController;
use App\Dictionary\Constants;
use App\Entity\User;
use App\Filters\PostFilter;
use App\Form\AuthorSearchByEmailType;
use App\Service\Admin\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 *
 * Class AdminUsersController
 * @package App\Controller
 *
 *
 */
class AdminUsersController extends BaseController
{
    /**
     * @var UserService
     */
    private $service;

    public function __construct(
        UserService $service
    ) {
        $this->service = $service;
    }

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
    public function index($page = Constants::DEFAULT_PAGE)
    {
        $formSearch = $this->createForm(AuthorSearchByEmailType::class);
        $pagination = $this->service->getUsersList($page);

        return $this->render('admin/users/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $formSearch->createView(),
        ]);
    }

    /**
     * @Route("admin/user/block/{id}/{page}", name="admin_user_block", requirements={"id"="\d+"})
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function block(User $user, int $page = Constants::DEFAULT_PAGE): RedirectResponse
    {
        if ($user->isBlocked()) {
            $this->addFlash('error', 'User already has been blocked');
        } else {
            $this->service->block($user);
            $this->addFlash('success', 'User #' . $user->getId() . ' blocked');
        }

        return $this->redirectToRoute('admin_users_list', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("admin/user/activate/{id}/{page}", name="admin_user_activate", requirements={"id"="\d+"})
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function activate(User $user, int $page = Constants::DEFAULT_PAGE): RedirectResponse
    {
        if ($user->isActive()) {
            $this->addFlash('error', 'User is already active');
        } else {
            $this->service->activate($user);
            $this->addFlash('success', 'User #' . $user->getId() . ' activated');
        }

        return $this->redirectToRoute('admin_users_list', [
            'page' => $page,
        ]);
    }
}
