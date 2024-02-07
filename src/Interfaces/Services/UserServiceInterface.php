<?php

namespace App\Interfaces\Services;

interface UserServiceInterface
{
    public function getUserById(int $userId): array;

    public function getAllUsers(): array;

    public function updateUser(int $userId, array $userData): int;

    public function deleteUser(int $userId): void;
}
