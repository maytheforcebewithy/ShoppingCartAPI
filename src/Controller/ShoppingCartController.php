<?php

namespace App\Controller;

use App\Service\ShoppingCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ShoppingCartController extends AbstractController
{
    private ShoppingCartService $shoppingCartService;

    public function __construct(ShoppingCartService $shoppingCartService)
    {
        $this->shoppingCartService = $shoppingCartService;
    }

    public function updateCart(Request $request, int $userId): JsonResponse
    {
        $cartData = json_decode($request->getContent(), true);
        $action = $cartData['action'];
        $productId = $cartData['productId'];
        $quantity = $cartData['quantity'];

        switch ($action) {
            case 'add':
                $this->shoppingCartService->addProductToCart($userId, $productId, $quantity);
                break;
            case 'remove':
                $this->shoppingCartService->removeProductFromCart($userId, $productId);
                break;
            case 'update':
                $this->shoppingCartService->updateProductQuantityInCart($userId, $productId, $quantity);
                break;
            default:
                throw new BadRequestHttpException('Invalid action');
        }

        return new JsonResponse(['message' => 'Cart updated successfully'], Response::HTTP_OK);
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
