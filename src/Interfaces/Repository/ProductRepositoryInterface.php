<?php

namespace App\Interfaces;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function addProduct(Product $product): bool;
    public function getProductById(int $productId): ?Product;
    public function updateProduct(Product $product): bool;
    public function deleteProduct(int $productId): bool;
}
