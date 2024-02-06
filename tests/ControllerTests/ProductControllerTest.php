<?php

namespace Tests\ControllerTests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductControllerTest extends KernelTestCase
{
    private KernelBrowser $client;

    private \PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();
    
        $kernel = self::bootKernel();
    
        $this->client = new KernelBrowser($kernel);
    
        $databaseConfig = require __DIR__ . '/../../config/packages/test/database.php';
    
        $this->pdo = new \PDO(
            $databaseConfig['dsn'],
            $databaseConfig['username'],
            $databaseConfig['password'],
            $databaseConfig['options']
        );
    }

    public function testCreateProduct()
    {
        $this->client->request(
            'POST',
            '/product/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product',
                'price' => 9.99,
                'quantity' => 10,
            ])
        );

        $response = $this->client->getResponse();

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        if ($this->client->getResponse()->getStatusCode() === 500) {
            // Print the error message
            echo $this->client->getResponse()->getContent();
        }
    }
}
