<?php

namespace App\Tests;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetUsersAsSuperAdmin(): void
    {
        // Login as SUPER_ADMIN to get token
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user@example.com',
            'password' => '12345678',
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        $token = $data['token'] ?? '';

        // Make GET /users request with token
        $this->client->request('GET', '/api/v1/users', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
        ]);

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());

        $users = json_decode($response->getContent(), true);
        $this->assertIsArray($users);
    }

    public function testCreateUserAsCompanyAdmin(): void
    {
        // Login as COMPANY_ADMIN to get token
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user@example.com',
            'password' => '12345678',
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        $token = $data['token'] ?? '';

        // Make POST /users request
        $this->client->request('POST', '/api/v1/users', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'securepassword',
            'role' => 'ROLE_USER',
            'company' => 1,
        ]));

        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data);
    }
}
