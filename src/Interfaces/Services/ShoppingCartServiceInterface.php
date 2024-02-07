<?php

namespace App\Interfaces\Services;

interface ShoppingCartServiceInterface
{
    public function addProductToCart(int $userId, int $productId, int $quantity): void;

    public function updateProductQuantityInCart(int $userId, int $productId, int $quantity): void;

    public function removeProductFromCart(int $userId, int $productId): void;

    public function getAllCarts(): array;

    public function removeProductFromAllCarts(int $productId): void;
}
