<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShoppingCartService
{
    private ShoppingCartRepository $shoppingCartRepository;
    private UserRepository $userRepository;
    private ProductRepository $productRepository;

    public function __construct(ShoppingCartRepository $shoppingCartRepository, UserRepository $userRepository, ProductRepository $productRepository)
    {
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
    }

    public function addProductToCart(int $userId, int $productId, int $quantity)
    {
        $user = $this->userRepository->getUserById($userId);
        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        $product = $this->productRepository->getProductById($productId);
        if (!$product) {
            throw new BadRequestHttpException('Product not found');
        }

        if ($quantity <= 0) {
            throw new BadRequestHttpException('Quantity must be greater than 0');
        }

        $this->shoppingCartRepository->addProduct($userId, $productId, $quantity);
    }

    public function updateProductQuantityInCart(int $userId, int $productId, int $quantity)
    {
        $user = $this->userRepository->getUserById($userId);
        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        $product = $this->productRepository->getProductById($productId);
        if (!$product) {
            throw new BadRequestHttpException('Product not found');
        }

        if ($quantity <= 0) {
            throw new BadRequestHttpException('Quantity must be greater than 0');
        }

        $this->shoppingCartRepository->updateProductQuantity($userId, $productId, $quantity);
    }

    public function getAllCarts(): array
    {
        return $this->shoppingCartRepository->getAllCarts();
    }

    public function getCartByUser(int $userId): array
    {
        $user = $this->userRepository->getUserById($userId);
        if (!$user) {
            throw new BadRequestHttpException('User not found');
        }

        return $this->shoppingCartRepository->getCartByUser($userId);
    }
}
