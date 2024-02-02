<?php

namespace App\Repository;

use App\Entity\Product;
use App\Interfaces\ProductRepositoryInterface;
use PDO;

class ProductRepository implements ProductRepositoryInterface
{
    private PDO $dbConnection;

    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function addProduct(Product $product): bool
    {
        $stmt = $this->dbConnection->prepare('INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)');
        return $stmt->execute([$product->getName(), $product->getPrice(), $product->getQuantity()]);
    }

    public function getProductById(int $productId): ?Product
    {
        $stmt = $this->dbConnection->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$productId]);
        
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$productData) {
            return null;
        }

        return new Product($productData['name'], $productData['price'], $productData['quantity']);
    }

    public function updateProduct(Product $product): bool
    {
        $stmt = $this->dbConnection->prepare('UPDATE products SET name = ?, price = ?, quantity = ? WHERE id = ?');
        return $stmt->execute([$product->getName(), $product->getPrice(), $product->getQuantity(), $product->getId()]);
    }

    public function deleteProduct(int $productId): bool
    {
        $stmt = $this->dbConnection->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$productId]);
    }
}
