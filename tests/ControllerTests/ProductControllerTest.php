<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{   
    public function testGetAllProducts(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/products');

        $this->assertResponseIsSuccessful();
    }

    public function testGetOneProductById(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/products/1');

        $this->assertResponseIsSuccessful();
    }
}
