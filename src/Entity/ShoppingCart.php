<?php

namespace App\Entity;

class ShoppingCart
{
    private int $id;

    private User $user;

    private array $items;

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
        //to be done
    }

    public function removeItem(Product $product): void
    {
        //to be done
    }

    public function editQuantityOfItem(Product $product, int $newQuantity): void
    {
        //to be done
    }

    public function getItems(): array
    {
        return $this->items;
    }
}