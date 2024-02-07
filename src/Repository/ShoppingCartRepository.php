<?php

namespace App\Repository;

use App\Interfaces\Repository\ShoppingCartRepositoryInterface;
use App\Entity\ShoppingCart;

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
        $stmt = $this->dbConnection->prepare('SELECT id FROM cart_items');
        $stmt->execute();

        $carts = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $carts[] = $row;
        }

        return $carts;
    }

    public function getCartByUser(int $userId): ?ShoppingCart
    {
        $stmt = $this->dbConnection->prepare('SELECT cart_items.id AS cart_id, cart_items.user_id, json_object_agg(products.name, cart_items.quantity) AS items 
                                             FROM cart_items 
                                             JOIN products ON cart_items.product_id = products.id 
                                             WHERE cart_items.user_id = ? 
                                             GROUP BY cart_items.id, cart_items.user_id');
        $stmt->execute([$userId]);
    
        $cart = null;
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (!$cart) {
                $cart = new ShoppingCart($row['user_id']);
                $cart->setId($row['cart_id']);
            }
            
            $items = json_decode($row['items'], true);
            foreach ($items as $productName => $quantity) {
                $productId = (int) filter_var($productName, FILTER_SANITIZE_NUMBER_INT);
                $cart->addItem($productId, $quantity);
            }
        }
    
        return $cart;
    }

    public function removeProductFromAllCart(int $productId): bool
    {
        $stmt = $this->dbConnection->prepare('DELETE FROM cart_items WHERE product_id = ?');

        return $stmt->execute([$productId]);
    }
}
