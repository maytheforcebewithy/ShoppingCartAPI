<?php

// src/Service/ProductService.php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private ProductRepository $productRepository;
    private ValidatorInterface $validator;

    public function __construct(ProductRepository $productRepository, ValidatorInterface $validator)
    {
        $this->productRepository = $productRepository;
        $this->validator = $validator;
    }

    public function addProduct(array $productData): Product
    {
        $product = new Product($productData['name'], $productData['price'], $productData['quantity']);

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $this->productRepository->addProduct($product);

        return $product;
    }

    public function updateProduct(int $productId, array $productData): Product
    {
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new BadRequestHttpException('Product not found');
        }

        $product->setName($productData['name']);
        $product->setPrice($productData['price']);
        $product->setQuantity($productData['quantity']);

        $errors = $this->validator->validate($product);

        if (count($errors) > 0) {
            throw new BadRequestHttpException('Validation failed');
        }

        $this->productRepository->updateProduct($product);

        return $product;
    }

    public function deleteProduct(int $productId): void
    {
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new BadRequestHttpException('Product not found');
        }

        $this->productRepository->deleteProduct($productId);
    }

    public function getProduct(int $productId): Product
    {
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new BadRequestHttpException('Product not found');
        }

        return $product;
    }

    public function getProducts(): array
    {
        return $this->productRepository->getAllProducts();
    }
}
