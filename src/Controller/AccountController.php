<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="user_post_list")
     */
    public function index(PostRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $pagination = $paginator->paginate(
            $repository->findUserPostOrderedByNewest($this->getUser()->getId()),
            $request->query->getInt('page', 1),
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
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(PostFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Post $post */
            $post = $form->getData();
            $post->setUser($this->getUser());
//            dump($post);
            dump($form->getData());

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
     *
     */
    public function edit(Post $post, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PostFormType::class, $post, [
            'include_published_at' => true
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Article Updated! Inaccuracies squashed!');

            return $this->redirectToRoute('admin_article_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('article_admin/edit.html.twig', [
            'articleForm' => $form->createView()
        ]);
    }


}
