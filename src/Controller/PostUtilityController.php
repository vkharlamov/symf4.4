<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostUtilityController
 * @TODO Add Grant rules
 * @package App\Controller
 */
class PostUtilityController extends AbstractController
{
    /**
     * @Route("/tags/search", methods="POST", name="tags_search_utility")
     */
    public function getTagsApi(UserRepository $userRepository, Request $request): JsonResponse
    {
        $users = $userRepository->findAllMatching($request->query->get('query'));

        return $this->json([
            'users' => $users
        ], 200, [], ['groups' => ['main']]);
    }
}