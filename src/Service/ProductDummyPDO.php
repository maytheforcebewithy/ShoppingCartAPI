<?php

namespace App\Service;

class ProductDummyPDO
{
    /**
     * @var array<int, array{id: int, name: string, price: float, quantity: int}>
     */
    private array $data = [
        [
            'id' => 1,
            'name' => 'Product 1',
            'price' => 10.99,
            'quantity' => 5,
        ],
        [
            'id' => 2,
            'name' => 'Product 2',
            'price' => 19.99,
            'quantity' => 3,
        ],
    ];

    public function prepare(string $statement): self
    {
        return $this;
    }

    /**
     * @param array<int, mixed> $params
     */
    public function execute(array $params): bool
    {
        return true;
    }

    /**
     * @param array<int, mixed> $params
     *
     * @return array<int, array{id: int, name: string, price: float, quantity: int}>|null
     */
    public function fetch(string $statement, array $params): ?array
    {
        if (false !== strpos($statement, 'SELECT * FROM products')) {
            return $this->data;
        } else {
            return null;
        }
    }
}
