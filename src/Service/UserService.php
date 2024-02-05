<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private $userRepository;
    private ValidatorInterface $validator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function getUserById($userData): User
    {
        $user = new User($userData['name'], $userData['email']);

        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        return $this->userRepository->getUserById($userData);
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser($userData)
    {
        $user = new User($userData['name'], $userData['email']);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        return $this->userRepository->addUser($userData);
    }

    public function updateUser($userId, $userData)
    {
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        $user->setUsername($userData['username']);
        $user->setEmail($userData['email']);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $this->userRepository->updateUser($user);

        return $user;
    }

    public function deleteUser($id)
    {
        return $this->userRepository->deleteUser($id);
    }
}
