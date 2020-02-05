<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\DTO\PostVote as PostVoteDto;
use App\Service\PostVoteService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Vote;
use App\Form\VotePostFormType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class PostVoteController extends BaseController
{
    /**
     * @Route("/post/vote/{id}", name="user_vote_post")
     *
     * @IsGranted("RATE", subject="post")
     */
    public function vote(Post $post, PostVoteService $service, PostVoteDto $postVoteDto): JsonResponse
    {
        $form = $this->createForm(VotePostFormType::class, $postVoteDto);
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** vote property has been set already */
            $postVoteDto->setAuthor($this->getUser());
            $postVoteDto->setPost($post);
            $service->store($postVoteDto);

            return $this->json(['rating' => ''], Response::HTTP_CREATED);

        } else {

            //           throw new \Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException('Invalid CSRF token');
        }


        if (!$this->isCsrfTokenValid('vote_post_token_string', $this->request->request->get('_vote_token'))) {

//           throw new \Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException('Invalid CSRF token');
            return $this->json(['rating' => ''], Response::HTTP_FORBIDDEN);
        }

//        if ()

        $postVoteDto->setPost($post, $this->getUser(), PostRating::LIKE_SCORE);

//        $service->change($postRating);

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }
}


