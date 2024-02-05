<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function createProduct(Request $request): JsonResponse
    {
        $productData = json_decode($request->getContent(), true);
        $product = $this->productService->addProduct($productData);

        return new JsonResponse(['message' => 'Product created successfully'], Response::HTTP_CREATED);
    }

    public function updateProduct(Request $request, int $id): JsonResponse
    {
        $productData = json_decode($request->getContent(), true);
        $product = $this->productService->updateProduct($id, $productData);

        return new JsonResponse(['message' => 'Product updated successfully'], Response::HTTP_OK);
    }

    public function deleteProduct(int $id): JsonResponse
    {
        $this->productService->deleteProduct($id);

        return new JsonResponse(['message' => 'Product deleted successfully'], Response::HTTP_OK);
    }

    public function getProduct(int $productId): JsonResponse
    {
        $product = $this->productService->getProduct($productId);

        return new JsonResponse($product, Response::HTTP_OK);
    }

    public function getProducts(): JsonResponse
    {
        $products = $this->productService->getProducts();

        return new JsonResponse($products, Response::HTTP_OK);
    }
}
