<?php

namespace App\Service;

class ProductDummyPDO
{
    private array $data =
    [
        1 => [
            'name' => 'Dummy Product',
            'price' => 10.99,
            'quantity' => 100
        ]
    ];

    private bool $executed = false;

    public function prepare($statement)
    {
        return $this;
    }

    public function execute($params)
    {
        $this->executed = true;
        return true;
    }

    public function fetch($statement, $params)
    {
        if (!$this->executed)
        {
            return false;
        }

        if ($statement === 'SELECT * FROM products WHERE id = ?')
        {
            return $this->data[$params[0]];
        }

        return $this->data[1];
    }
}
