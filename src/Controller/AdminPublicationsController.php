<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormStatus;
use App\Repository\PostRepository;
use App\Service\PostService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class AdminPublicationsController
 * @package App\Controller
 */
class AdminPublicationsController extends AbstractController
{
    /**
     * @Route("/admin/publications/{page}", name="admin_publications_list", requirements={"page"="\d+"})
     */
    public function index(PostRepository $repository, PaginatorInterface $paginator, $page = 1)
    {
        $pagination = $paginator->paginate(
            $repository->getPostListOrderedByNewest(),
            $page,
            Post::LIMIT_PER_PAGE
        );

        return $this->render('admin/publications/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/publications/{id}/edit", methods={"GET","HEAD", "POST"}, name="admin_publication_edit")
     */
    public function edit(Post $post, PostService $postService, Request $request)
    {
        $form = $this->createForm(PostFormStatus::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postService->update($post);

            $this->addFlash('success', 'Success. Article status updated!');

            // Return to the same edit page
            return $this->redirectToRoute('admin_publication_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('admin/publications/edit_post.html.twig', [
            'formStatus' => $form->createView(),
            'post' => $post,
        ]);
    }
}
