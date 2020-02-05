<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\DTO\PostVote as PostVoteDto;
use App\Exceptions\ApiVoteException;
use App\Service\PostVoteService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        try {
            $form = $this->createForm(VotePostFormType::class, $postVoteDto);
            $form->handleRequest($this->request);
            if ($form->isSubmitted() && $form->isValid()) {
                /** vote property has been set already */
                $postVoteDto->setAuthor($this->getUser());
                $postVoteDto->setPost($post);
                $service->store($postVoteDto);

                return new JsonResponse(['vote' => $postVoteDto->getVote()], Response::HTTP_CREATED);

            } else {
                throw new ApiVoteException(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } catch (ApiVoteException $exception) {
            return new JsonResponse([], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}


