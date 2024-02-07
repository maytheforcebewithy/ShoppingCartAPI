<?php

namespace App\Controller;

use App\Service\ShoppingCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartController extends AbstractController
{
    private ShoppingCartService $shoppingCartService;

    public function __construct(ShoppingCartService $shoppingCartService)
    {
        $this->shoppingCartService = $shoppingCartService;
    }

    public function addProductToCart(Request $request, int $userId, int $productId): JsonResponse
    {
        $cartData = json_decode($request->getContent(), true);
        $quantity = $cartData['quantity'];

        $this->shoppingCartService->addProductToCart($userId, $productId, $quantity);

        return new JsonResponse(['message' => 'Product added to cart successfully'], Response::HTTP_OK);
    }

    public function removeProductFromCart(Request $request, int $userId): JsonResponse
    {
        $cartData = json_decode($request->getContent(), true);
        $productId = $cartData['productId'];

        $this->shoppingCartService->removeProductFromCart($userId, $productId);

        return new JsonResponse(['message' => 'Product removed from cart successfully'], Response::HTTP_OK);
    }

    public function updateProductQuantityInCart(Request $request, int $userId): JsonResponse
    {
        $cartData = json_decode($request->getContent(), true);
        $productId = $cartData['productId'];
        $quantity = $cartData['quantity'];

        $this->shoppingCartService->updateProductQuantityInCart($userId, $productId, $quantity);

        return new JsonResponse(['message' => 'Product quantity updated successfully'], Response::HTTP_OK);
    }

    public function getCart(int $userId): JsonResponse
    {
        $cart = $this->shoppingCartService->getCartByUser($userId);

        return new JsonResponse($cart, Response::HTTP_OK);
    }

    public function getCarts(): JsonResponse
    {
        $carts = $this->shoppingCartService->getAllCarts();

        return new JsonResponse($carts, Response::HTTP_OK);
    }
}
