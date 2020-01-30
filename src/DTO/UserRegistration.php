<?php

declare(strict_types=1);

namespace App\DTO;

class UserRegistration
{
    private $email;
    private $name;
    private $plainPassword;

    public function set(string $email, string $name, string $plainPassword): void
    {
        $this->email = $email;
        $this->name = $name;
        $this->plainPassword = $plainPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
