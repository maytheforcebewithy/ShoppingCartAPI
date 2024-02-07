<?php

namespace App\Interfaces\Repository;

use App\Entity\ShoppingCart;

interface ShoppingCartRepositoryInterface
{
    public function addProduct(int $userId, int $productId, int $quantity): bool;

    public function removeProduct(int $userId, int $productId): bool;

    public function updateProductQuantity(int $userId, int $productId, int $quantity): bool;

    public function getAllCarts(): array;

    public function getCartByUser(int $userId): ?ShoppingCart;

    public function removeProductFromAllCart(int $productId): bool;
}
