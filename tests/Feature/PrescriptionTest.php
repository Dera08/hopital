<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrescriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function doctor_can_create_prescription()
    {
        // Create a doctor user
        $doctor = User::factory()->create(['role' => 'doctor']);
        $patient = Patient::factory()->create();

        // Act as the doctor
        $this->actingAs($doctor);

        // Attempt to access the create prescription page
        $response = $this->get(route('prescriptions.create', ['patient_id' => $patient->id]));

        // Assert that access is granted (no 403)
        $response->assertStatus(200);
    }

    /** @test */
    public function internal_doctor_can_create_prescription()
    {
        // Create an internal doctor user
        $doctor = User::factory()->create(['role' => 'internal_doctor']);
        $patient = Patient::factory()->create();

        // Act as the doctor
        $this->actingAs($doctor);

        // Attempt to access the create prescription page
        $response = $this->get(route('prescriptions.create', ['patient_id' => $patient->id]));

        // Assert that access is granted (no 403)
        $response->assertStatus(200);
    }

    /** @test */
    public function external_doctor_can_create_prescription()
    {
        // Create an external doctor user
        $doctor = User::factory()->create(['role' => 'external_doctor']);
        $patient = Patient::factory()->create();

        // Act as the doctor
        $this->actingAs($doctor);

        // Attempt to access the create prescription page
        $response = $this->get(route('prescriptions.create', ['patient_id' => $patient->id]));

        // Assert that access is granted (no 403)
        $response->assertStatus(200);
    }

    /** @test */
    public function nurse_cannot_create_prescription()
    {
        // Create a nurse user
        $nurse = User::factory()->create(['role' => 'nurse']);
        $patient = Patient::factory()->create();

        // Act as the nurse
        $this->actingAs($nurse);

        // Attempt to access the create prescription page
        $response = $this->get(route('prescriptions.create', ['patient_id' => $patient->id]));

        // Assert that access is denied (403)
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_cannot_create_prescription()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);
        $patient = Patient::factory()->create();

        // Act as the admin
        $this->actingAs($admin);

        // Attempt to access the create prescription page
        $response = $this->get(route('prescriptions.create', ['patient_id' => $patient->id]));

        // Assert that access is denied (403)
        $response->assertStatus(403);
    }

    /** @test */
    public function doctor_can_store_prescription()
    {
        // Create a doctor user
        $doctor = User::factory()->create(['role' => 'doctor']);
        $patient = Patient::factory()->create();

        // Act as the doctor
        $this->actingAs($doctor);

        // Data for creating a prescription
        $data = [
            'patient_id' => $patient->id,
            'medication' => 'Paracetamol',
            'type' => 'curatif',
            'instructions' => 'Take one tablet every 6 hours',
        ];

        // Attempt to store the prescription
        $response = $this->post(route('prescriptions.store'), $data);

        // Assert that the prescription was created successfully
        $response->assertRedirect(route('patients.show', $patient->id));
        $this->assertDatabaseHas('prescriptions', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'medication' => 'Paracetamol',
            'type' => 'curatif',
            'instructions' => 'Take one tablet every 6 hours',
            'status' => 'pending_sign',
            'is_signed' => false,
        ]);
    }
}
