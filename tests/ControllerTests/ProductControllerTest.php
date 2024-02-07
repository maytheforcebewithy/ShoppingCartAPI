<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testGetAllProducts(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreateProduct(): void
    {
        $client = static::createClient();

        $productData = [
            'name' => 'Test Product',
            'price' => 99.99,
            'quantity' => 10,
        ];

        $client->request(
            'POST',
            '/products/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($productData)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testGetOneProductById(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/products/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateProduct(): void
    {
        $client = static::createClient();

        $productData = [
            'name' => 'Updated Product',
            'price' => 100,
            'quantity' => 4,
        ];

        $client->request(
            'PUT',
            '/products/update/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($productData)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteProduct(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $client->request(
            'DELETE',
            'products/delete/11',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testGetProductByInvalidId(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/products/9999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testCreateProductWithoutName(): void
    {
        $client = static::createClient();

        $productData = [
            'price' => 99.99,
            'quantity' => 10,
        ];

        $client->request(
            'POST',
            '/products/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($productData)
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testUpdateNonExistentProduct(): void
    {
        $client = static::createClient();

        $productData = [
            'name' => 'Updated Product',
            'price' => 100,
            'quantity' => 4,
        ];

        $client->request(
            'PUT',
            '/products/update/9999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($productData)
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeleteNonExistentProduct(): void
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            'products/delete/9999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(404);
    }
}
