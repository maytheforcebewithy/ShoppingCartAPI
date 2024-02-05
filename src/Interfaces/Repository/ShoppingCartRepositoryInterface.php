<?php

namespace App\Interfaces\Repository;

use App\Entity\Product;

interface ShoppingCartRepositoryInterface
{
    public function addProduct(int $userId, int $productId, int $quantity): bool;

    public function removeProduct(int $userId, int $productId): bool;

    public function updateProductQuantity(int $userId, int $productId, int $quantity): bool;

    public function getAllCarts(): array;
}
