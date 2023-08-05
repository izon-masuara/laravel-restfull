<?php

namespace Tests\Feature;

use Tests\TestCase;

class InfoTest extends TestCase
{
    public function testServerIsRunning()
    {
        $response = $this->get('/api');
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Server is running.']);
    }

    public function testServerIsDown()
    {
        $response = $this->get('/api', ['SERVER_DOWN' => true]);
        // $response->assertStatus(502);
        $response->assertJson(['message' => 'Server is down.']);
    }
}
