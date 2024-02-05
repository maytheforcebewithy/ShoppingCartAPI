<?php

namespace App\Interfaces\Entity;

interface UserInterface
{
    public function getId(): int;

    public function getUsername(): string;

    public function getEmail(): string;

    public function setUsername(string $username): void;

    public function setEmail(string $email): void;
}
