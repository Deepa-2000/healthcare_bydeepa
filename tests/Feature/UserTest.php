<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function a_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'sonu garg',
            'email' => 'sonu@example.com',
            'password' => '123456',
            'role' => 'doctor'
        ]);

        $response->assertStatus(201) // Expecting a 201 Created response
            ->assertJsonStructure([
                'status',
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token'
            ]);
    }

    public function a_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'sonu@example.com',
            'password' => bcrypt('123456')

        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'sonu@example.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token'
            ]);
    }
}
