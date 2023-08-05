<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:wipe');
        $this->artisan('migrate');
        $this->artisan('db:seed --class=UserSeeder');
    }
    public function testSuccessGetToken(): void
    {
        $data = [
            'email' => 'piljurian@example.app',
            'password' => 'example2048',
        ];
        
        $response = $this->post('/api/users/authenticate', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
        $response->assertJson([
            'token' => 'lfiExUFNJtdujwhpblRTeRrZWfvfFbTJ'
        ]);
    }

    public function testFailedValidate(): void
    {
        $data = [
            'email' => '',
            'password' => 'example2048',
        ];
        
        $response = $this->post('/api/users/authenticate', $data);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    public function testAccountNotFound(): void
    {
        $data = [
            'email' => 'notfound@mail.com',
            'password' => 'example2048',
        ];
        
        $response = $this->post('/api/users/authenticate', $data);
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJson([
            'message' => 'Account is not found.'
        ]);
    }

    public function testInvalidCredential(): void
    {
        $data = [
            'email' => 'piljurian@example.app',
            'password' => 'example2048e',
        ];
        
        $response = $this->post('/api/users/authenticate', $data);
        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJson([
            'message' => 'Invalid credentials.'
        ]);
    }
}
