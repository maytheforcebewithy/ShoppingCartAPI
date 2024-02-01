<?php

namespace App\Interfaces;
use App\Entity\Product;

interface ShoppingCartRepositoryInterface
{
    public function getCartItems(int $cartId): array;
    public function getCartByUserId(int $userId): ?array;
    public function getCartItemByProductId(int $cartId, int $productId): ?array;
    public function updateCartItemQuantity(int $cartId, int $productId, int $newQuantity): bool;
    public function addCartItem(int $cartId, int $productId, int $quantity): bool;
    public function addOneItemToCart(int $userId, Product $product): bool;
    public function editQuantityOfItem(int $userId, Product $product, int $newQuantity): bool;
    public function removeOneItemFromCart(int $userId, Product $product): bool;
    public function removeWholeItemFromCart(int $userId, Product $product): bool;
}
