<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase; // Clears database before each test

    protected $admin;
    protected $doctorUser;
    protected $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Create a doctor user
        $this->doctorUser = User::factory()->create([
            'role' => 'doctor',
            'password' => Hash::make('password'),
        ]);

        // Create a doctor associated with the doctor user
        $this->doctor = Doctor::factory()->create([
            'user_id' => $this->doctorUser->id,
            'specialization' => 'Cardiology',
            'contact' => '1234567890',
        ]);
    }

    /** @test */
    public function admin_can_create_a_doctor()
    {
        $this->actingAs($this->admin); // Authenticate as admin

        $response = $this->postJson('/api/doctors', [
            'name' => 'Dr. shipra',
            'email' => 'dr.shipra@example.com',
            'specialization' => 'Dermatology',
            'contact' => '9876543210',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Doctor added successfully',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'dr.shipra@example.com']);
        $this->assertDatabaseHas('doctors', ['specialization' => 'Dermatology']);
    }

    /** @test */
    public function unauthorized_user_cannot_create_doctor()
    {
        $this->actingAs($this->doctorUser); // Authenticate as a doctor (not admin)

        $response = $this->postJson('/api/doctors', [
            'name' => 'Dr. keshav',
            'email' => 'keshav@example.com',
            'specialization' => 'Neurology',
            'contact' => '1231231234',
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function admin_can_fetch_all_doctors()
    {
        $this->actingAs($this->admin);

        $response = $this->getJson('/api/doctors');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    /** @test */
    public function unauthorized_user_cannot_fetch_doctors()
    {
        $this->actingAs($this->doctorUser);

        $response = $this->getJson('/api/doctors');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function admin_can_search_doctors_by_name_or_email()
    {
        $this->actingAs($this->admin);

        $response = $this->getJson('/api/doctors/search?search=John');

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_fetch_a_single_doctor()
    {
        $this->actingAs($this->admin);

        $response = $this->getJson("/api/doctors/{$this->doctor->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['doctor']);
    }

    /** @test */
    public function admin_can_update_a_doctor()
    {
        $this->actingAs($this->admin);

        $response = $this->putJson("/api/doctors/{$this->doctor->id}", [
            'specialization' => 'Psychiatry',
            'contact' => '5555555555',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Doctor updated successfully']);

        $this->assertDatabaseHas('doctors', ['specialization' => 'Psychiatry']);
    }

    /** @test */
    public function admin_can_delete_a_doctor()
    {
        $this->actingAs($this->admin);

        $response = $this->deleteJson("/api/doctors/{$this->doctor->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Doctor deleted successfully']);

        $this->assertDatabaseMissing('doctors', ['id' => $this->doctor->id]);
    }


}
