<?php

declare(strict_types=1);

namespace App\Service\Admin;

use App\Dictionary\Constants;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService
 * @package App\Service\Admin
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @param int $page
     * @param int $userId
     *
     * @return PaginationInterface
     */
    public function getUsersList(int $page, int $userId = 0): PaginationInterface
    {
        // Dummy approach with User' id to test param transformer feature only
        // @see \App\Form\DataTransformer\EmailToUserTransformer for details

        $userId = !$userId ? [] : ['id' => $userId];

        return $this->paginator->paginate(
            $this->userRepository->findBy($userId, ['id' => 'DESC']),
            $userId ? Constants::DEFAULT_PAGE : $page,
            Constants::USER_PER_PAGE
        );
    }

    public function block(User $user): void
    {
        $user->setStatus(User::STATUS_INACTIVE);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function activate(User $user): void
    {
        $user->setStatus(User::STATUS_ACTIVE);
        $this->em->persist($user);
        $this->em->flush();
    }
}
