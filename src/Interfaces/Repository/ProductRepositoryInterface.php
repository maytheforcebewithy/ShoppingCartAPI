<?php

namespace App\Repository;

use App\Interfaces\Product;

interface ProductRepositoryInterface
{
    public function find(int $productId): ?Product;
    public function addProduct(Product $product): bool;
    public function getProductById(int $productId): ?Product;
    public function updateProduct(Product $product): bool;
    public function deleteProduct(int $productId): bool;
}
