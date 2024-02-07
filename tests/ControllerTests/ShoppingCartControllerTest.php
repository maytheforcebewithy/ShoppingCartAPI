<?php

namespace App\Tests\ControllerTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShoppingCartControllerTest extends WebTestCase
{
    public function testaddProductToCart(): void
    {
        $client = static::createClient();
    
        $userId = 5;
        $productId = 1;
        $cartData = [
            'quantity' => 2,
        ];
    
        $client->request(
            'POST',
            '/carts/' . $userId . '/add/' . $productId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($cartData)
        );
    
        $this->assertResponseIsSuccessful();
    }

    public function testRemoveProductFromCart(): void
    {
        $client = static::createClient();

        $userId = 1;
        $productId = 1;
        $cartData = [
            'productId' => 1,
        ];

        $client->request(
            'DELETE',
            '/carts/' . $userId . '/remove/' . $productId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],           
            json_encode($cartData)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateProductQuantityInCart(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $userId = 1;
        $productId = 1;
        $cartData = [
            'productId' => 1,
            'quantity' => 2,
        ];

        $client->request(
            'PUT',
            '/carts/' . $userId . '/update/' . $productId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($cartData)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testGetCart(): void
    {
        $client = static::createClient();

        $userId = 1;

        $client->request(
            'GET',
            '/carts' . $userId,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testGetCarts(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);

        $client->request(
            'GET',
            '/carts',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }
}
