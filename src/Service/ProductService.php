<?php

namespace App\Service;

use App\Entity\Product;
use App\Interfaces\Services\ProduktServiceInterface;
use App\Repository\ProductRepository;
use App\Validator\ProductValidation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService implements ProduktServiceInterface
{
    private ProductRepository $productRepository;
    private ValidatorInterface $validator;
    private ShoppingCartService $shoppingCartService;
    private ProductValidation $productValidation;

    public function __construct(ProductRepository $productRepository, ValidatorInterface $validator, ShoppingCartService $shoppingCartService, ProductValidation $productValidation)
    {
        $this->productRepository = $productRepository;
        $this->validator = $validator;
        $this->shoppingCartService = $shoppingCartService;
        $this->productValidation = $productValidation;
    }

    public function addProduct(array $productData): int
    {
        $errors = $this->productValidation->validateProductData($productData);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $product = new Product($productData['name'], $productData['price'], $productData['quantity']);

        return $this->productRepository->addProduct($product);
    }

    public function updateProduct(int $productId, array $productData): int
    {
        $errors = $this->productValidation->validateProductData($productData);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        $product['name'] = $productData['name'];
        $product['price'] = $productData['price'];
        $product['quantity'] = $productData['quantity'];

        $errors = $this->validator->validate($product);
        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $productObject = new Product($product['name'], $product['price'], $product['quantity']);
        $productObject->setId($productId);

        return $this->productRepository->updateProduct($productObject);
    }

    public function deleteProduct(int $productId): void
    {
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        $this->shoppingCartService->removeProductFromAllCarts($productId);
        $this->productRepository->deleteProduct($productId);
    }

    public function getProduct(int $productId): array
    {
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        return $product;
    }

    public function getProducts(): array
    {
        return $this->productRepository->getAllProducts();
    }
}
