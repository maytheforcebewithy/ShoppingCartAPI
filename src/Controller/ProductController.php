<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductController extends AbstractController
{
    private $productRepository;
    private $validator;

    public function __construct(ProductRepository $productRepository, ValidatorInterface $validator)
    {
        $this->productRepository = $productRepository;
        $this->validator = $validator;
    }

    public function createProduct(Request $request): JsonResponse
    {
        $product = $this->getProductFromRequest($request);
        if (!$product) {
            return new JsonResponse(['message' => 'Invalid data provided'], 400);
        }

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $this->getErrorsAsString($errors)], 400);
        }

        $this->productRepository->addProduct($product);

        return new JsonResponse(['message' => 'Product created successfully'], 201);
    }

    public function updateProduct(Request $request, $id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);
        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $newProduct = $this->getProductFromRequest($request);
        if (!$newProduct) {
            return new JsonResponse(['message' => 'Invalid data provided'], 400);
        }

        $errors = $this->validator->validate($newProduct);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => 'Validation errors', 'errors' => $this->getErrorsAsString($errors)], 400);
        }

        $product->setName($newProduct->getName());
        $product->setPrice($newProduct->getPrice());
        $product->setQuantity($newProduct->getQuantity());

        $this->productRepository->updateProduct($product);

        return new JsonResponse(['message' => 'Product updated successfully'], 200);
    }

    public function deleteProduct($id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);

        if (!$product)
        {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $this->productRepository->deleteProduct($id);

        return new JsonResponse(['message' => 'Product deleted successfully'], 200);
    }

    public function getProduct($id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], 404);
        }

        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'quantity' => $product->getQuantity()
        ];

        return new JsonResponse($data, 200);
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