<?php

namespace App\DataFixtures;

class AppFixtures
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function load(): void
    {
        $this->deleteExistingData();
        $this->insertProducts();
        $this->insertUsers();
        $this->insertCartItems();
    }

    private function deleteExistingData(): void
    {
        $tables = ['cart_items', 'products', 'users'];

        foreach ($tables as $table) {
            $stmt = $this->pdo->prepare("TRUNCATE $table RESTART IDENTITY CASCADE");
            $stmt->execute();
        }
    }

    private function insertProducts(): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $name = 'Produkt '.$i;
            $price = mt_rand(100, 10000) / 100;
            $quantity = mt_rand(1, 100);

            $stmt = $this->pdo->prepare('INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)');
            $stmt->execute([$name, $price, $quantity]);
        }
    }

    private function insertUsers(): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $username = 'Benutzer'.$i;
            $email = 'benutzer'.$i.'@example.com';

            $stmt = $this->pdo->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
            $stmt->execute([$username, $email]);
        }
    }

    private function insertCartItems(): void
    {
        for ($i = 1; $i <= 3; ++$i) {
            $userId = mt_rand(1, 5);

            $productIds = range(1, 10);
            shuffle($productIds);
            $numItems = mt_rand(1, 5);
            $selectedProducts = array_slice($productIds, 0, $numItems);

            foreach ($selectedProducts as $productId) {
                $quantity = mt_rand(1, 10);

                $stmt = $this->pdo->prepare('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)');
                $stmt->execute([$userId, $productId, $quantity]);
            }
        }
    }
}
