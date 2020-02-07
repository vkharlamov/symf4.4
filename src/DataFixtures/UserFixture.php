<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixture
 * @package App\DataFixtures
 */
class UserFixture extends BaseFixture
{
    public const USER_PASSWORD = '1234567';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixture constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createConfirmedUsers(20);
        $this->createUnConfirmedUsers(2);
        $this->createAdminUser();
        $manager->flush();
    }

    /**
     * @param int $count
     */
    private function createConfirmedUsers(int $count = 1): void
    {
        $this->createMany($count, 'confirmed_users', function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, self::USER_PASSWORD));
            $user->setRoles([User::ROLE_USER]);
            $user->setName($this->faker->firstName(array_rand(['male', 'female'])));
            $user->setLastName($this->faker->lastName);
            $user->setStatus(User::STATUS_ACTIVE);

            return $user;
        });
    }

    /**
     * @param int $count
     */
    private function createUnConfirmedUsers(int $count = 1): void
    {
        $this->createMany($count, 'unconfirmed_users', function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setConfirmToken($this->faker->uuid);
            $user->setPassword($this->passwordEncoder->encodePassword($user, self::USER_PASSWORD));
            $user->setRoles([User::ROLE_USER]);
            $user->setName($this->faker->firstName(array_rand(['male', 'female'])));
            $user->setLastName($this->faker->lastName);
            $user->setStatus(User::STATUS_NOT_VERIFIED);

            return $user;
        });
    }

    private function createAdminUser(): void
    {
        $this->create(function () {
            $user = new User();
            $user->setEmail($this->faker->unique()->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, self::USER_PASSWORD));
            $user->setRoles([User::ROLE_ADMIN]);
            $user->setName($this->faker->firstName(array_rand(['male', 'female'])));
            $user->setLastName($this->faker->lastName);
            $user->setStatus(User::STATUS_ACTIVE);

            return $user;
        });
    }
}
