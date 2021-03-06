<?php

namespace App\Controller;

use App\Dictionary\Constants;
use App\Entity\Post;
use App\Form\ArticleFormStatus;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Service\PostService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{

    /**
     * @var PostService
     */
    private $postService;

    public function __construct(
        PostService $postService,
        RequestStack $requestStack
    ) {
        parent::__construct($requestStack);
        $this->postService = $postService;
    }
    /**
     * @Route("/account", name="user_post_list")
     */
    public function index(PostRepository $repository, PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $repository->findUserPostOrderedByNewest($this->getUser()->getId()),
            $this->request->query->getInt('page', Constants::DEFAULT_PAGE),
            Constants::USER_PER_PAGE
        );

        return $this->render('account/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Creates new post no using DTO, with form feature only
     *
     * @Route("/account/post/new", name="user_post_new")
     */
    public function new()
    {
        $form = $this->createForm(PostFormType::class);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Post $post */
            $post = $form->getData();
            $this->postService->create($post);

            $this->addFlash('success', 'Post Created.');

            return $this->redirectToRoute('user_post_list');
        }

        return $this->render('article_user/new.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/post/{id}/edit", name="user_post_edit")
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @IsGranted("ROLE_USER")
     * @IsGranted("EDIT_POST", subject="post")
     */
    public function edit(Post $post)
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->postService->update($post);

            $this->addFlash('success', 'Success. Article Updated!');

            return $this->redirectToRoute('user_post_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('article_user/edit.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/post/{id}/publicate", name="user_post_publish")
     *
     * @param Post $post
     */
    public function publish(Post $post, PostService $service)
    {
        $service->userPublishArticle($post);
        $this->addFlash('success', 'Success. Article queued for moderation');

        return $this->redirectToRoute('user_post_list');
    }

    /**
     * @Route("/account/post/{id}/delete", name="user_post_delete")
     * @IsGranted("ROLE_USER")
     * @IsGranted("DELETE_POST", subject="post")
     *
     * @param Post $post
     */
    public function delete(Post $post, PostService $service)
    {
        $service->userPublishArticle($post);
        $this->addFlash('success', 'Success. Article queued for moderation');

        return $this->redirectToRoute('user_post_list');
    }
}
