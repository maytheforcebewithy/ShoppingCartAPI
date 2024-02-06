<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUserById(int $userId): JsonResponse
    {
        $user = $this->userService->getUserById($userId);

        return new JsonResponse($user, Response::HTTP_OK);
    }

    public function getAllUsers(): JsonResponse
    {
        $users = $this->userService->getAllUsers();

        return new JsonResponse($users, Response::HTTP_OK);
    }

    public function createUser(Request $request): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);
        $user = $this->userService->createUser($userData);

        return new JsonResponse(['message' => 'User created successfully'], Response::HTTP_CREATED);
    }

    public function updateUser(Request $request, int $userId): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);
        $user = $this->userService->updateUser($userId, $userData);

        return new JsonResponse(['message' => 'User updated successfully'], Response::HTTP_OK);
    }

    public function deleteUser(int $userId): JsonResponse
    {
        $this->userService->deleteUser($userId);

        return new JsonResponse(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }
}
