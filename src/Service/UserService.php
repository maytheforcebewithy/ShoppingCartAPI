<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Validator\UserValidator;

class UserService
{
    private UserRepository $userRepository;
    private ValidatorInterface $validator;
    private UserValidator $userValidator;

    public function __construct(UserRepository $userRepository, ValidatorInterface $validator, UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->userValidator = $userValidator;
    }

    public function getUserById(int $userId): array
    {
        $userData = $this->userRepository->getUserById($userId);
    
        if (!$userData) {
            throw new NotFoundHttpException('User not found');
        }

        $user = [
            'id' => $userData['id'],
            'username' => $userData['name'],
            'email' => $userData['email']
        ];
    
        return $user;
    }
    

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function createUser(array $userData): void
    {
        $errors = $this->userValidator->validateUser($userData);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $user = new User($userData['username'], $userData['email']);

        $this->userRepository->addUser($user);
    }

    public function updateUser(int $userId, array $userData): void
    {
        $errors = $this->userValidator->validateUser($userData);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $user['userName'] = $userData['name'];
        $user['email'] = $userData['email'];

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }
    
        $userObject = new User($user['name'], $user['email']);
        $userObject->setId($userId);
    
        $this->userRepository->updateUser($userObject);
    }

    public function deleteUser(int $userId): void
    {
        $success = $this->userRepository->deleteUser($userId);
        if (!$success) {
            throw new NotFoundHttpException('User not found');
        }
    }
}
