<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    private ProductRepository $productRepository;
    private ValidatorInterface $validator;

    public function __construct(ProductRepository $productRepository, ValidatorInterface $validator)
    {
        $this->productRepository = $productRepository;
        $this->validator = $validator;
    }

    public function createProduct(Request $request): JsonResponse
    {
        try {
            $product = $this->getProductFromRequest($request);

            if (!$product) {
                return new JsonResponse(['message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
            }

            $this->productRepository->addProduct($product);

            return new JsonResponse(['message' => 'Product created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProduct(Request $request, int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->getProductById($id);
            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            $newProduct = $this->getProductFromRequest($request);
            if (!$newProduct) {
                return new JsonResponse(['message' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
            }

            $updateConstraints = new Assert\Collection([
                'name' => [
                    new Assert\NotBlank(['message' => 'The name cannot be blank.']),
                    new Assert\Type(['type' => 'string', 'message' => 'The name must be a string.']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'The name cannot be longer than 255 characters.']),
                ],
                'price' => [
                    new Assert\NotBlank(['message' => 'The price cannot be blank.']),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The price must be a number.']),
                    new Assert\GreaterThanOrEqual(['value' => 0, 'message' => 'The price cannot be negative.']),
                ],
                'quantity' => [
                    new Assert\NotBlank(['message' => 'The quantity cannot be blank.']),
                    new Assert\Type(['type' => 'numeric', 'message' => 'The quantity must be a number.']),
                    new Assert\GreaterThanOrEqual(['value' => 0, 'message' => 'The quantity cannot be negative.']),
                ],
            ]);

            $errors = $this->validator->validate($newProduct, $updateConstraints);
            if (count($errors) > 0) {
                return new JsonResponse(['message' => 'Validation errors', 'errors' => $this->getErrorsAsString($errors)], Response::HTTP_BAD_REQUEST);
            }

            $product->setName($newProduct->getName());
            $product->setPrice($newProduct->getPrice());
            $product->setQuantity($newProduct->getQuantity());

            $this->productRepository->updateProduct($product);

            return new JsonResponse(['message' => 'Product updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProduct(int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->getProductById($id);

            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            $this->productRepository->deleteProduct($id);

            return new JsonResponse(['message' => 'Product deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProduct(int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->getProductById($id);

            if (!$product) {
                return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            $data = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
            ];

            return new JsonResponse($data, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getErrorsAsString(ConstraintViolationListInterface $errors): string
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }

        return implode(', ', $errorMessages);
    }

    private function getProductFromRequest(Request $request): ?Product
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'], $data['price'], $data['quantity'])) {
            return null;
        }

        return new Product($data['name'], $data['price'], $data['quantity']);
    }
}
