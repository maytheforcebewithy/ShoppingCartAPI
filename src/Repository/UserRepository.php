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
    
        return $success;
    }

    public function updateUser(User $user): bool
    {
        $stmt = $this->dbConnection->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');

        return $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getId()]);
    }

    public function deleteUser(int $userId): bool
    {
        $user = $this->getUserById($userId);
        if (!$user) {
            return false;
        }
    
        $stmt = $this->dbConnection->prepare('DELETE FROM cart_items WHERE user_id = ?');
        $stmt->execute([$userId]);
    
        $stmt = $this->dbConnection->prepare('DELETE FROM users WHERE id = ?');
    
        return $stmt->execute([$userId]);
    }

    public function getUserById(int $userId): ?array
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$userId]);

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        } 

        $user = new User($userData['name'], $userData['email']);

        return [
            'name' => $user->getUsername(),
            'email' => $user->getEmail(),
        ];
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
