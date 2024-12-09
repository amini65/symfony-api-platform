<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompanyControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetCompanies(): void
    {

        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user@example.com',
            'password' => '12345678',
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        $token = $data['token'] ?? '';

        $this->client->request('GET', '/api/v1/companies', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
            'CONTENT_TYPE' => 'application/json',
        ]);

        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertJson($response->getContent());

        $companies = json_decode($response->getContent(), true);
        $this->assertIsArray($companies);
    }

    public function testCreateCompanyAsSuperAdmin(): void
    {
        // Login as SUPER_ADMIN to get token

        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user@example.com',
            'password' => '12345678',
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        $token = $data['token'] ?? '';

        // Make POST /companies request
        $this->client->request('POST', '/api/v1/companies', [], [], [
            'HTTP_AUTHORIZATION' => "Bearer $token",
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'name' => 'New Company',
        ]));

        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $data['data']['id']);
    }
}
