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

        $this->pdo = new \PDO('pgsql:host=test_database;dbname=test_db_name;user=test_db_user;password=test_db_password', 'test_db_user', 'test_db_password');
    }

    public function testCreateProduct()
    {
        $this->client->request(
            'POST',
            '/api/product',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Product',
                'price' => 9.99,
                'quantity' => 10,
            ])
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }
}
