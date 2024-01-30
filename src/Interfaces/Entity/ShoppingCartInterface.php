<?php

namespace App\Interfaces;

interface ShoppingCartInterface
{
    public function getId(): int;
    public function getUser(): User;
    public function addItem(Product $product, int $quantity): void;
    public function removeItem(Product $product): void;
    public function editQuantityOfItem(Product $product, int $newQuantity): void;
    public function getItems(): array;
}
