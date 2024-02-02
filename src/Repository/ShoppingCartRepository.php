<?php

namespace App\Repository;

use App\Entity\Product;
use App\Interfaces\ShoppingCartRepositoryInterface;
use PDO;

class ShoppingCartRepository implements ShoppingCartRepositoryInterface
{
    private PDO $dbConnection;

    public function __construct(PDO $pdo)
    {
        $this->dbConnection = $pdo;
    }

    public function getCartItems(int $cartId): array
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM cart_items WHERE cart_id = ?');
        $stmt->execute([$cartId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartByUserId(int $userId): ?array
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM shopping_carts WHERE user_id = ?');
        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCartItemByProductId(int $cartId, int $productId): ?array
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?');
        $stmt->execute([$cartId, $productId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCartItemQuantity(int $cartId, int $productId, int $newQuantity): bool
    {
        $stmt = $this->dbConnection->prepare('UPDATE cart_items SET quantity = ? WHERE cart_id = ? AND product_id = ?');

        return $stmt->execute([$newQuantity, $cartId, $productId]);
    }

    public function addCartItem(int $cartId, int $productId, int $quantity): bool
    {
        $stmt = $this->dbConnection->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)');

        return $stmt->execute([$cartId, $productId, $quantity]);
    }

    public function addOneItemToCart(int $userId, Product $product): bool
    {
        $cart = $this->getCartByUserId($userId);

        if (!$cart) {
            return false;
        }

        if ($product->getQuantity() < 1) {
            return false;
        }

        $existingItem = $this->getCartItemByProductId($cart['id'], $product->getId());

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + 1;

            return $this->updateCartItemQuantity($cart['id'], $product->getId(), $newQuantity);
        } else {
            return $this->addCartItem($cart['id'], $product->getId(), 1);
        }
    }

    public function editQuantityOfItem(int $userId, Product $product, int $newQuantity): bool
    {
        $cart = $this->getCartByUserId($userId);

        if (!$cart) {
            return false;
        }

        return $this->updateCartItemQuantity($cart['id'], $product->getId(), $newQuantity);
    }

    public function removeOneItemFromCart(int $userId, Product $product): bool
    {
        $cart = $this->getCartByUserId($userId);

        if (!$cart) {
            return false;
        }

        $existingItem = $this->getCartItemByProductId($cart['id'], $product->getId());

        if ($existingItem && $existingItem['quantity'] > 1) {
            $newQuantity = $existingItem['quantity'] - 1;

            return $this->updateCartItemQuantity($cart['id'], $product->getId(), $newQuantity);
        } else {
            return $this->removeWholeItemFromCart($userId, $product);
        }
    }

    public function removeWholeItemFromCart(int $userId, Product $product): bool
    {
        $cart = $this->getCartByUserId($userId);

        if (!$cart) {
            return false;
        }

        $stmt = $this->dbConnection->prepare('DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?');

        return $stmt->execute([$cart['id'], $product->getId()]);
    }
}
