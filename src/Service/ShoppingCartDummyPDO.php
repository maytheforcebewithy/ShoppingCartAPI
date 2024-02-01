<?php

namespace App\Service;

class ShoppingCartDummyPDO
{
    /**
     * @var array<int, array{id: int, user_id: int}>
     */
    private array $shoppingCarts = [
        ['id' => 1, 'user_id' => 1],
        ['id' => 2, 'user_id' => 2],
    ];

    /**
     * @var array<int, array{id: int, user_id: int}>
     */
    private array $cartItems = [
        ['id' => 1, 'cart_id' => 1, 'product_id' => 1, 'quantity' => 2],
        ['id' => 2, 'cart_id' => 1, 'product_id' => 2, 'quantity' => 1],
        ['id' => 3, 'cart_id' => 2, 'product_id' => 1, 'quantity' => 3],
    ];

    public function prepare(string $statement): self
    {
        return $this;
    }

    /**
     * @param array<int, mixed> $params
     */
    public function execute(array $params): bool
    {
        return true;
    }

    /**
     * @param array<int, mixed> $params
     *
     * @return array{id: int, user_id: int}|array{id: int, cart_id: int, product_id: int, quantity: int}|null
     */
    public function fetch(string $statement, array $params): ?array
    {
        if (false !== strpos($statement, 'SELECT * FROM shopping_carts')) {
            foreach ($this->shoppingCarts as $cart) {
                if ($cart['user_id'] === $params[0]) {
                    return $cart;
                }
            }

            return null;
        } elseif (false !== strpos($statement, 'SELECT * FROM cart_items')) {
            $filteredCartItems = array_filter($this->cartItems, function ($item) use ($params) {
                return $item['cart_id'] === $params[0] && $item['product_id'] === $params[1];
            });

            return reset($filteredCartItems);
        } else {
            return null;
        }
    }

    /**
     * @return array<int, array{id: int, cart_id: int, product_id: int, quantity: int}>
     */
    public function fetchAll(): array
    {
        return $this->cartItems;
    }
}
