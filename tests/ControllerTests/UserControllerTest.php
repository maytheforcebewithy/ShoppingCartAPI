<?php

namespace App\Tests\ControllerTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUserById(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/users/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testGetAllUsers(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/users',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/users/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'New User', 'email' => 'test@new.com'])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        
        $client->request(
            'PUT',
            '/users/update/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'Updated User', 'email' => 'test@update.com'])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteUser(): void
    {
        $client = static::createClient();
        
        $client->request(
            'DELETE',
            '/users/delete/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseIsSuccessful();
    }

    public function testGetUserByInvalidId(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            '/users/9999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testCreateUserWithInvalidData(): void
    {
        $client = static::createClient();
        
        $client->request(
            'POST',
            '/users/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => '', 'email' => ''])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testUpdateUserWithInvalidName(): void
    {
        $client = static::createClient();

        $client->request(
            'PUT',
            '/users/update/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => '', 'email' => 'test@update.com'])
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testDeleteUserWithInvalidId(): void
    {
        $client = static::createClient();
        
        $client->request(
            'DELETE',
            '/users/delete/9999',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertResponseStatusCodeSame(404);
    }
}
