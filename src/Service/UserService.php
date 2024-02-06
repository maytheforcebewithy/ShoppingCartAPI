<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private UserRepository $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function getUserById(int $userId): User
    {
        $userData = $this->userRepository->getUserById($userId);

        if (null === $userData) {
            throw new BadRequestHttpException('User not found');
        }

        return $this->userRepository->getUserById($userData->getId());
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(User $userData): User
    {
        $user = new User($userData->getUsername(), $userData->getEmail());

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $this->userRepository->addUser($userData);

        return $user;
    }

    public function updateUser(int $userId, User $userData): User
    {
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        $user->setUsername($userData->getUsername());
        $user->setEmail($userData->getEmail());

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $this->userRepository->updateUser($user);

        return $user;
    }

    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->deleteUser($userId);
    }
}
