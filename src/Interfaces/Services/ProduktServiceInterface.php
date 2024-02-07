<?php

namespace App\Interfaces\Services;

interface ProduktServiceInterface
{
    public function addProduct(array $productData): int;

    public function updateProduct(int $productId, array $productData): int;

    public function deleteProduct(int $productId): void;

    public function getProduct(int $productId): array;

    public function getProducts(): array;
}
