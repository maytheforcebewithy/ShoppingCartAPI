<?php

namespace App\Interfaces\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function addUser(User $user): bool;

    public function updateUser(User $user): bool;

    public function deleteUser(int $userId): bool;

    public function getUserById(int $userId): ?array;

    public function getAllUsers(): array;
}
