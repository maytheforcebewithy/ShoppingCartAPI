<?php

namespace App\Repository;

use App\Entity\User;
use App\Interfaces\Repository\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private \PDO $dbConnection;

    public function __construct(\PDO $pdo)
    {
        $this->dbConnection = $pdo;
    }

    public function addUser(User $user): bool
    {
        $stmt = $this->dbConnection->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
        $success = $stmt->execute([$user->getUsername(), $user->getEmail()]);

        if ($success) {
            $userId = $this->dbConnection->lastInsertId();

            $stmt = $this->dbConnection->prepare('INSERT INTO carts (user_id) VALUES (?)');
            $stmt->execute([$userId]);
        }

        return $success;
    }

    public function updateUser(User $user): bool
    {
        $stmt = $this->dbConnection->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');

        return $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getId()]);
    }

    public function deleteUser(int $userId): bool
    {
        $stmt = $this->dbConnection->prepare('DELETE FROM carts WHERE user_id = ?');
        $stmt->execute([$userId]);

        $stmt = $this->dbConnection->prepare('DELETE FROM users WHERE id = ?');

        return $stmt->execute([$userId]);
    }

    public function getUserById(int $userId): ?User
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        return new User($userData['name'], $userData['email']);
    }

    public function getAllUsers(): array
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM users');
        $stmt->execute();

        $usersData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $users = [];

        foreach ($usersData as $userData) {
            $users[] = new User($userData['name'], $userData['email']);
        }

        return $users;
    }
}
