<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\ShoppingCartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

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
        try {
            $data = json_decode($request->getContent(), true);

            $productId = $data['productId'];
            $userId = $data['userId'];

            $errorMessages = $this->validateInput($data);

            if (count($errorMessages) > 0) {
                return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
            }

            $product = $this->productRepository->getProductById($productId);

            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            $success = $this->shoppingCartRepository->addOneItemToCart($userId, $product);

            if (!$success) {
                return new JsonResponse(['message' => 'Failed to add product to cart'], JsonResponse::HTTP_BAD_REQUEST);
            }

            return new JsonResponse(['message' => 'Product added to cart successfully'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editItemInCart(Request $request, int $productId, int $quantity): JsonResponse
    {
        try {
            $userId = $request->query->get('userId');

            $data = ['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity];
            $errorMessages = $this->validateInput($data);

            if (count($errorMessages) > 0) {
                return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
            }

            $product = $this->productRepository->getProductById($productId);

            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            $success = $this->shoppingCartRepository->editQuantityOfItem($userId, $product, $quantity);

            if (!$success) {
                return new JsonResponse(['message' => 'Failed to edit product quantity in cart'], JsonResponse::HTTP_BAD_REQUEST);
            }

            return new JsonResponse(['message' => 'Product quantity in cart edited successfully'], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeOneItemFromCart(Request $request, int $productId): JsonResponse
    {
        try {
            $userId = $request->query->get('userId');
            $data = ['userId' => $userId, 'productId' => $productId];
            $errorMessages = $this->validateInput($data);
            if (count($errorMessages) > 0) {
                return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }
    
            $product = $this->productRepository->getProductById($productId);
            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
    
            $success = $this->shoppingCartRepository->removeOneItemFromCart($userId, $product);
            if (!$success) {
                return new JsonResponse(['message' => 'Failed to remove one item from cart'], Response::HTTP_BAD_REQUEST);
            }
    
            return new JsonResponse(['message' => 'One item removed from cart successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function removeWholeItemFromCart(Request $request, int $productId): JsonResponse
    {
        try {
            $userId = $request->query->get('userId');
            $data = ['userId' => $userId, 'productId' => $productId];
            $errorMessages = $this->validateInput($data);
            if (count($errorMessages) > 0) {
                return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }
    
            $product = $this->productRepository->getProductById($productId);
            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
    
            $success = $this->shoppingCartRepository->removeWholeItemFromCart($userId, $product);
            if (!$success) {
                return new JsonResponse(['message' => 'Failed to remove whole item from cart'], Response::HTTP_BAD_REQUEST);
            }
    
            return new JsonResponse(['message' => 'Whole item removed from cart successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function viewCart(Request $request): JsonResponse
    {
        try {
            $userId = $request->query->get('userId');
            $data = ['userId' => $userId];
            $errorMessages = $this->validateInput($data);
            if (count($errorMessages) > 0) {
                return new JsonResponse(['message' => 'Validation errors', 'errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }
    
            $cart = $this->shoppingCartRepository->getCartByUserId($userId);
            if (!$cart) {
                return new JsonResponse(['message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
            }
    
            $cartItems = $this->shoppingCartRepository->getCartItems($cart['id']);
            $formattedCartItems = [];
            foreach ($cartItems as $cartItem) {
                $product = $this->productRepository->getProductById($cartItem['product_id']);
                $formattedCartItems[] = [
                    'product_id' => $cartItem['product_id'],
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'quantity' => $cartItem['quantity'],
                ];
            }
    
            return new JsonResponse(['cart' => $formattedCartItems], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validateInput(array $data): array
    {
        $updateConstraints = new Assert\Collection([
            'userId' => [
                new Assert\NotBlank(['message' => 'The userId cannot be blank.']),
                new Assert\Type(['type' => 'integer', 'message' => 'The userId must be an integer.']),
            ],
            'productId' => [
                new Assert\NotBlank(['message' => 'The productId cannot be blank.']),
                new Assert\Type(['type' => 'integer', 'message' => 'The productId must be an integer.']),
            ],
            'quantity' => [
                new Assert\NotNull(['message' => 'The quantity cannot be null.']),
                new Assert\Type(['type' => 'integer', 'message' => 'The quantity must be an integer.']),
                new Assert\GreaterThanOrEqual(['value' => 0, 'message' => 'The quantity cannot be negative.']),
            ],
        ]);

        $errors = $this->validator->validate($data, $updateConstraints);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return $errorMessages;
    }
}
