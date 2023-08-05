<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FoodTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:wipe');
        $this->artisan('migrate');
    }
    public function testSuccessPostFood(): void
    {
        $data = [
            'name' => 'Rendang',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.'
        ];
        $headers = ['Authorization' => "BearerAuth"];
        $response = $this->post('/api/foods', $data, $headers);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'price',
            'description',
            'links' => [
                'self'
            ]
        ]);
        $response->assertJson([
            'name' => 'Rendang',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.',
            'links' => [
                'self' => 'https://interview-api.pilihjurusan.id/foods/1'
            ]
        ]);
    }

    public function testUnauthenticate(): void
    {
        $data = [
            'name' => 'Rendang',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.'
        ];
        $response = $this->post('/api/foods', $data);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message'
        ]);
        $response->assertJson([
            'message' => "Unauthenticated."
        ]);
    }

    public function testFailedValidatePostFood(): void
    {
        $data = [
            'name' => 'Rendang',
            'price' => 0,
            'description' => 'Lorem ipsum dolor amet.'
        ];
        $headers = ['Authorization' => "BearerAuth"];
        $response = $this->post('/api/foods', $data, $headers);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    public function testSuccessGetFoodById(): void
    {
        $postData = [
            'name' => 'Rendang',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.'
        ];
        $postHeaders = ['Authorization' => "BearerAuth"];
        $this->post('/api/foods', $postData, $postHeaders);
        $headers = ['Authorization' => "BearerAuth"];
        $response = $this->get("/api/foods/1", $headers);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'price',
            'description',
            'links' => [
                'self'
            ]
        ]);
        $response->assertJson([
            'name' => 'Rendang',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.',
            'links' => [
                'self' => 'https://interview-api.pilihjurusan.id/foods/1'
            ]
        ]);
    }

    public function testNotFoundGetFoodById(): void
    {
        $headers = ['Authorization' => "BearerAuth"];
        $response = $this->get("/api/foods/999", $headers);
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'message'
        ]);
        $response->assertJson([
            'message' => 'The given food resource is not found.'
        ]);
    }

    public function testGetListFood(): void
    {
        $headers = ['Authorization' => "BearerAuth"];
        $response = $this->get("/api/foods", $headers);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'retrieved',
            'data'
        ]);
    }

    public function testSuccessEditFood():void
    {
        $postData = [
            'name' => 'Rendang',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.'
        ];
        $headers = ['Authorization' => "BearerAuth"];
        $this->post('/api/foods', $postData, $headers);

        $data = [
            'name' => 'Rendang Ayam',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.'
        ];
        $response = $this->put('/api/foods/1', $data, $headers);
        $response->assertStatus(200);
        
        $response->assertJsonStructure([
            'id',
            'name',
            'price',
            'description',
            'links' => [
                'self'
            ]
        ]);
        $response->assertJson([
            'name' => 'Rendang Ayam',
            'price' => 23000,
            'description' => 'Lorem ipsum dolor amet.',
            'links' => [
                'self' => 'https://interview-api.pilihjurusan.id/foods/1'
            ]
        ]);
    }
}
