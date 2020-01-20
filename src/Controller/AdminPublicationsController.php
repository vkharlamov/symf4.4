<?php

namespace App\Controller;

use App\DTO\PostFilterRequest;
use App\Entity\Post;
use App\Form\PostFormStatus;
use App\Service\PostService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class AdminPublicationsController
 * @package App\Controller
 */
class AdminPublicationsController extends BaseController
{
    /**
     * @Route("/admin/publications/{page}", name="admin_publications_list", requirements={"page"="\d+"})
     *
     * @return Response
     */
    public function index(
        PostFilterRequest $postFilterRequest,
        PostService $service,
        $page = 1): Response
    {
        return $this->render('admin/publications/index.html.twig', [
            'postsPaginator' => $service->getFilteredPosts($postFilterRequest, $page),
            'statuses' => Post::STATUSES,
        ]);
    }

    /**
     * @Route("/admin/publications/{id}/edit", name="admin_publication_edit")
     * @ParamConverter("post", class="App\Entity\Post")
     *
     * @param Post $post
     * @param PostService $postService
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Post $post, PostService $postService)
    {
        $form = $this->createForm(PostFormStatus::class, $post);
        $form->handleRequest($this->request);

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
