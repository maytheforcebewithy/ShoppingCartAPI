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

        $client->request(
            'PUT',
            '/product/update/1',
            [],
            [],
            ['CONTENT_TYPE'=> 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteProduct(): void
    {
        $client = static::createClient();

        $client->request(
            'DELETE',
            'product/delete/1',
            [],
            [],
            ['CONTENT_TYPE'=> 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }
}
