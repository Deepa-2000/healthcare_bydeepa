<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase; // Resets database after each test
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_a_patient()
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        // Patient data
        $data = [
            'name' => 'Sonam',
            'email' => 'sonam@example.com',
            'password' => '123456',
            'dob' => '15/08/1990',
            'gender' => 'female',
            'blood_group' => 'O+',
            'contact' => '1234567890',
            'address' => '123 Main ABC'
        ];

        // Call the API
        $response = $this->postJson('/api/patients', $data);

        // Assert the response
        $response->assertStatus(201)
                 ->assertJson(['status' => true, 'message' => 'Patient added successfully']);
    }

    public function patient_creation_fails_with_invalid_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ];

        $response = $this->postJson('/api/patients', $data);

        $response->assertStatus(422)
                 ->assertJsonStructure(['status', 'message', 'errors']);
    }

    public function admin_can_update_a_patient()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $patient = Patient::factory()->create();

        $updateData = ['name' => 'Madhav'];

        $response = $this->putJson("/api/patients/{$patient->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson(['status' => true, 'message' => 'Patient updated successfully']);
    }

    public function admin_can_delete_a_patient()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $patient = Patient::factory()->create();

        $response = $this->deleteJson("/api/patients/{$patient->id}");

        $response->assertStatus(200)
                 ->assertJson(['status' => true, 'message' => 'Patient deleted successfully']);
    }


}
