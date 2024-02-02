<?php

namespace App\Entity;

use App\Interfaces\ShoppingCartInterface;

class ShoppingCart implements ShoppingCartInterface
{
    private int $id;

    private User $user;

    /**
     * @var array<int, array{product: \App\Entity\Product, quantity: int}>
     */
    private array $items = [];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function addItem(Product $product, int $quantity): void
    {
        $itemId = $product->getId();

        if (isset($this->items[$itemId])) {
            $this->items[$itemId]['quantity'] += $quantity;
        } else {
            $this->items[$itemId] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }
    }

    public function removeItem(Product $product): void
    {
        $itemId = $product->getId();

        if (isset($this->items[$itemId])) {
            unset($this->items[$itemId]);
        }
    }

    public function editQuantityOfItem(Product $product, int $newQuantity): void
    {
        $itemId = $product->getId();

        if (isset($this->items[$itemId])) {
            $this->items[$itemId]['quantity'] = $newQuantity;
        }
    }

    /**
     * @return array<int, array{'product': Product, 'quantity': int}>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
