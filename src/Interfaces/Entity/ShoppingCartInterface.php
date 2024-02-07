<?php

namespace App\Interfaces\Entity;

interface ShoppingCartInterface
{
    public function getId(): int;

    public function getUserId(): int;

    public function setUserId(int $userId): void;

    public function getProductId(): int;

    public function setProductId(int $productId): void;

    public function getQuantity(): int;

    public function setQuantity(int $quantity): void;
}
