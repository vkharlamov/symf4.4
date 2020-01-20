<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ArticleFormStatus;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="user_post_list")
     */
    public function index(PostRepository $repository, PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $repository->findUserPostOrderedByNewest($this->getUser()->getId()),
            $this->request->query->getInt('page', 1),
            Post::LIMIT_PER_PAGE
        );

        return $this->render('account/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/account/post/new", name="user_post_new")
     *
     */
    public function new(EntityManagerInterface $em)
    {
        $form = $this->createForm(PostFormType::class);

        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $post */
            $post = $form->getData();
            $post->setUser($this->getUser());

//            dump($form->getData());

            $em->persist($post);
            $em->flush();

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
     */
    public function edit(Post $post, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($post);
            $em->flush();

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


}
