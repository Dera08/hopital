<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Patient;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientAuthTest extends TestCase
{
    use RefreshDatabase;

    protected $hospital;
    protected $patient;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un hôpital de test
        $this->hospital = Hospital::factory()->create();

        // Créer un patient de test
        $this->patient = Patient::factory()->create([
            'hospital_id' => $this->hospital->id,
            'email' => 'test.patient@example.com',
            'password' => bcrypt('password'),
            'ipu' => 'PAT20240001',
            'is_active' => true,
        ]);
    }

    /** @test */
    public function patient_can_login_with_email()
    {
        $response = $this->post(route('patient.login'), [
            'identifier' => 'test.patient@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $this->assertAuthenticatedAs($this->patient, 'patients');
    }

    /** @test */
    public function patient_can_login_with_ipu()
    {
        $response = $this->post(route('patient.login'), [
            'identifier' => 'PAT20240001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $this->assertAuthenticatedAs($this->patient, 'patients');
    }

    /** @test */
    public function patient_can_access_dashboard()
    {
        $this->actingAs($this->patient, 'patients');

        $response = $this->get(route('patient.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('patients.auth.dashboard');
        $response->assertViewHas('patient');
        $response->assertViewHas('upcomingAppointments');
        $response->assertViewHas('recentRecords');
    }

    /** @test */
    public function unauthenticated_patient_cannot_access_dashboard()
    {
        $response = $this->get(route('patient.dashboard'));

        $response->assertRedirect(route('patient.login'));
    }

    /** @test */
    public function patient_login_validates_required_fields()
    {
        $response = $this->post(route('patient.login'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['identifier', 'password']);
    }

    /** @test */
    public function patient_login_fails_with_wrong_credentials()
    {
        $response = $this->post(route('patient.login'), [
            'identifier' => 'test.patient@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['identifier']);
        $this->assertGuest('patients');
    }
}
