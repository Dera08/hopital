<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PatientVital;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NurseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nurse_can_delete_sent_file()
    {
        // Create an active nurse user
        $nurse = User::factory()->create(['role' => 'nurse', 'is_active' => true]);

        // Create a PatientVital record (sent file)
        $vital = PatientVital::factory()->create([
            'patient_name' => 'John Doe',
            'patient_ipu' => 'PAT-001',
            'reason' => 'Consultation générale',
            'user_id' => $nurse->id,
        ]);

        // Act as the nurse
        $this->actingAs($nurse);

        // Send DELETE request to destroy the vital
        $response = $this->delete(route('nurse.vital.destroy', $vital->id));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the response contains success message
        $response->assertJson(['success' => true, 'message' => 'Dossier supprimé']);

        // Assert that the record is deleted from the database
        $this->assertDatabaseMissing('patient_vitals', ['id' => $vital->id]);
    }

    /** @test */
    public function nurse_cannot_delete_nonexistent_file()
    {
        // Create an active nurse user
        $nurse = User::factory()->create(['role' => 'nurse', 'is_active' => true]);

        // Act as the nurse
        $this->actingAs($nurse);

        // Try to delete a non-existent vital (ID 999)
        $response = $this->delete(route('nurse.vital.destroy', 999));

        // Assert that the response is 404 or 500 (depending on implementation)
        $response->assertStatus(500); // Based on the controller's try-catch

        // Assert that the response contains error message
        $response->assertJson(['success' => false]);
    }
}
