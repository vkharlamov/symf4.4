<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\UserRegistration;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Exceptions\SecurityException;
use App\Repository\UserRepository;
use App\Security\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function register(UserRegistration $userRegister): void
    {
        $user = new User();
        $user->setEmail($userRegister->getEmail());
        $user->setName($userRegister->getName());
        $user->setConfirmToken($this->tokenGenerator->getRandomSecureToken());
        $user->setPassword($this->passwordEncoder->encodePassword($user, $userRegister->getPlainPassword()));
        $user->setStatus(User::STATUS_NOT_VERIFIED);

        $this->em->persist($user);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user), UserRegisteredEvent::NAME);
    }

    public function confirm(string $token): void
    {
        $user = $this->userRepository->findOneBy([
            User::ATTR_CONFIRM_TOKEN => $token
        ]);

        if ($user === null) {
            throw new SecurityException('No users for to this token. Contact to admin.');
        }

        $user->setStatus(User::STATUS_ACTIVE);
        $user->setConfirmToken(null);
        $this->em->flush();
    }
}
