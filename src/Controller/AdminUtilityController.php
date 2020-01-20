<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminUtilityController
 * @TODO Add Grant rules
 * @package App\Controller
 */
class AdminUtilityController extends AbstractController
{
    /**
     * @Route("/admin/utility/users", methods="GET", name="admin_utility_users")
     */
    public function getUsersApi(UserRepository $userRepository, Request $request): JsonResponse
    {
        $users = $userRepository->findAllMatching($request->query->get('query'));

        return $this->json([
            'users' => $users
        ], 200, [], ['groups' => ['main']]);
    }
}