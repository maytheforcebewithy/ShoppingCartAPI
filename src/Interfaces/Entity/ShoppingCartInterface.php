<?php

namespace App\Interfaces\Entity;

interface ShoppingCartInterface
{
    public function getId(): int;

    public function getUserId(): int;

    public function setId(int $id): void;

    public function setUserId(int $userId): void;

    public function addItem(int $productId, int $quantity): void;

    public function removeItem(int $productId): void;

    public function updateItemQuantity(int $productId, int $quantity): void;

    public function getItems(): array;

    public function toArray(): array;
}
