<?php

namespace App\Interfaces\Repository;

use App\Entity\Product;

interface ShoppingCartRepositoryInterface
{
    /**
     * @return array<int, array{id: int, cart_id: int, product_id: int, quantity: int}>
     */
    public function getCartItems(int $cartId): array;

    /**
     * @return array{id: int, user_id: int}|null
     */
    public function getCartByUserId(int $userId): ?array;

    /**
     * @return array{id: int, cart_id: int, product_id: int, quantity: int}|null
     */
    public function getCartItemByProductId(int $cartId, int $productId): ?array;

    public function updateCartItemQuantity(int $cartId, int $productId, int $newQuantity): bool;

    public function addCartItem(int $cartId, int $productId, int $quantity): bool;

    public function addOneItemToCart(int $userId, Product $product): bool;

    public function editQuantityOfItem(int $userId, Product $product, int $newQuantity): bool;

    public function removeOneItemFromCart(int $userId, Product $product): bool;

    public function removeWholeItemFromCart(int $userId, Product $product): bool;
}
