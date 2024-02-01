<?php

namespace App\Service;

class ShoppingCartDummyPDO
{
    private array $shoppingCarts = [
        ['id' => 1, 'user_id' => 1],
        ['id' => 2, 'user_id' => 2],
    ];

    private array $cartItems = [
        ['id' => 1, 'cart_id' => 1, 'product_id' => 1, 'quantity' => 2],
        ['id' => 2, 'cart_id' => 1, 'product_id' => 2, 'quantity' => 1],
        ['id' => 3, 'cart_id' => 2, 'product_id' => 1, 'quantity' => 3],
    ];

    private bool $executed = false;

    public function prepare($statement)
    {
        return $this;
    }

    public function execute($params)
    {
        $this->executed = true;
        return true; // Annahme: Die Ausführung war erfolgreich
    }

    public function fetch($statement, $params)
    {
        // Annahme: fetch wird für SELECT-Abfragen verwendet
        if (strpos($statement, 'SELECT * FROM shopping_carts') !== false) {
            foreach ($this->shoppingCarts as $cart) {
                if ($cart['user_id'] === $params[0]) {
                    return $cart;
                }
            }
            return null; // Benutzer hat keinen Einkaufswagen
        } elseif (strpos($statement, 'SELECT * FROM cart_items') !== false) {
            $filteredCartItems = array_filter($this->cartItems, function ($item) use ($params) {
                return $item['cart_id'] === $params[0] && $item['product_id'] === $params[1];
            });
            return reset($filteredCartItems); // Gib das erste Element im Array zurück
        } else {
            return null; // Für andere Abfragen geben Sie null zurück
        }
    }
}
