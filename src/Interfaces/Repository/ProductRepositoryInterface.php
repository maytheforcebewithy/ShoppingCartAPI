<?php

namespace App\Interfaces\Repository;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function addProduct(Product $product): bool;

    public function getProductById(int $productId): ?array;

    public function updateProduct(Product $product): bool;

    public function deleteProduct(int $productId): bool;

    public function getAllProducts(): array;
}
