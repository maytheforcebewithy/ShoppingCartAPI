<?php

namespace App\Interfaces\Entity;

use App\Entity\Product;
use App\Entity\User;

interface ShoppingCartInterface
{
    public function getId(): int;

    public function getUser(): User;

    public function addItem(Product $product, int $quantity): void;

    public function removeItem(Product $product): void;

    public function editQuantityOfItem(Product $product, int $newQuantity): void;

    /**
     * @return array<int, array{'product': Product, 'quantity': int}>
     */
    public function getItems(): array;
}
