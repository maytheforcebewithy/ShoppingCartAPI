<?php

namespace App\Entity;

use App\Interfaces\Entity\ShoppingCartInterface;

class ShoppingCart implements ShoppingCartInterface
{
    private int $id;
    private int $userId;
    private array $items = [];

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function addItem(int $productId, int $quantity): void
    {
        $this->items[$productId] = $quantity;
    }

    public function removeItem(int $productId): void
    {
        unset($this->items[$productId]);
    }

    public function updateItemQuantity(int $productId, int $quantity): void
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId] = $quantity;
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        $cartArray = [
            'cart_id' => $this->id,
            'user_id' => $this->userId,
            'items' => []
        ];

        foreach ($this->items as $productId => $quantity) {
            $cartArray['items'][] = [
                'Product ' . $productId => $quantity
            ];
        }

        return $cartArray;
    }
}
