<?php

namespace App\Repository;

use App\Interfaces\Repository\ShoppingCartRepositoryInterface;

class ShoppingCartRepository implements ShoppingCartRepositoryInterface
{
    private \PDO $dbConnection;

    public function __construct(\PDO $pdo)
    {
        $this->dbConnection = $pdo;
    }

    public function addProduct(int $userId, int $productId, int $quantity): bool
    {
        $stmt = $this->dbConnection->prepare('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)');

        return $stmt->execute([$userId, $productId, $quantity]);
    }

    public function removeProduct(int $userId, int $productId): bool
    {
        $stmt = $this->dbConnection->prepare('DELETE FROM cart_items WHERE user_id = ? AND product_id = ?');

        return $stmt->execute([$userId, $productId]);
    }

    public function updateProductQuantity(int $userId, int $productId, int $quantity): bool
    {
        if (0 == $quantity) {
            return $this->removeProduct($userId, $productId);
        }

        $stmt = $this->dbConnection->prepare('UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?');

        return $stmt->execute([$quantity, $userId, $productId]);
    }

    public function getAllCarts(): array
    {
        $stmt = $this->dbConnection->prepare('SELECT cart_id FROM cart_items');
        $stmt->execute();

        $carts = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $carts[] = $row;
        }

        return $carts;
    }

    public function getCartByUser(int $userId): array
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM cart_items WHERE user_id = ?');
        $stmt->execute([$userId]);

        $cartItems = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cartItems[] = $row;
        }

        return $cartItems;
    }
}
