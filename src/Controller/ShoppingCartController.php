<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShoppingCartController extends AbstractController
{
    private ProductRepository $productRepository;
    private ShoppingCartRepository $shoppingCartRepository;
    private ValidatorInterface $validator;

    public function __construct(ProductRepository $productRepository, ShoppingCartRepository $shoppingCartRepository, ValidatorInterface $validator)
    {
        $this->productRepository = $productRepository;
        $this->shoppingCartRepository = $shoppingCartRepository;
        $this->validator = $validator;
    }

    public function addItemToCart(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productId = $data['productId'];
        $userId = $data['userId'];

        $errorMessages = $this->validateInput($data);

        if (count($errorMessages) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], 400);
        }

        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $success = $this->shoppingCartRepository->addOneItemToCart($userId, $product);

        if (!$success) {
            return new JsonResponse(['message' => 'Failed to add product to cart'], 400);
        }

        return new JsonResponse(['message' => 'Product added to cart successfully'], 200);
    }

    public function editItemInCart(Request $request, int $productId, int $quantity): JsonResponse
    {
        $userId = $request->query->get('userId');

        $data = ['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity];
        $errorMessages = $this->validateInput($data);

        if (count($errorMessages) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], 400);
        }

        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $success = $this->shoppingCartRepository->editQuantityOfItem($userId, $product, $quantity);

        if (!$success) {
            return new JsonResponse(['message' => 'Failed to edit product quantity in cart'], 400);
        }

        return new JsonResponse(['message' => 'Product quantity in cart edited successfully'], 200);
    }

    public function removeOneItemFromCart(Request $request, int $productId): JsonResponse
    {
        $userId = $request->query->get('userId');

        $data = ['userId' => $userId, 'productId' => $productId];
        $errorMessages = $this->validateInput($data);

        if (count($errorMessages) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], 400);
        }

        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $success = $this->shoppingCartRepository->removeOneItemFromCart($userId, $product);

        if (!$success) {
            return new JsonResponse(['message' => 'Failed to remove one item from cart'], 400);
        }

        return new JsonResponse(['message' => 'One item removed from cart successfully'], 200);
    }

    public function removeWholeItemFromCart(Request $request, int $productId): JsonResponse
    {
        $userId = $request->query->get('userId');

        $data = ['userId' => $userId, 'productId' => $productId];
        $errorMessages = $this->validateInput($data);

        if (count($errorMessages) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], 400);
        }

        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $success = $this->shoppingCartRepository->removeWholeItemFromCart($userId, $product);

        if (!$success) {
            return new JsonResponse(['message' => 'Failed to remove whole item from cart'], 400);
        }

        return new JsonResponse(['message' => 'Whole item removed from cart successfully'], 200);
    }

    public function viewCart(Request $request): JsonResponse
    {
        $userId = $request->query->get('userId');

        $data = ['userId' => $userId];
        $errorMessages = $this->validateInput($data);

        if (count($errorMessages) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], 400);
        }

        $cart = $this->shoppingCartRepository->getCartByUserId($userId);

        if (!$cart) {
            return new JsonResponse(['message' => 'Cart not found'], 404);
        }

        $cartItems = $this->shoppingCartRepository->getCartItems($cart['id']);

        $formattedCartItems = [];
        foreach ($cartItems as $cartItem) {
            $product = $this->productRepository->getProductById($cartItem['product_id']);
            $formattedCartItems[] =
            [
                'product_id' => $cartItem['product_id'],
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $cartItem['quantity'],
            ];
        }

        return new JsonResponse(['cart' => $formattedCartItems], 200);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string>
     */
    private function validateInput(array $data): array
    {
        $errors = $this->validator->validate([
            'userId' => $data['userId'] ?? null,
            'productId' => $data['productId'] ?? null,
            'quantity' => $data['quantity'] ?? null,
        ]);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }
}
