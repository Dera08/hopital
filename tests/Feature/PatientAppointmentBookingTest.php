<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Hospital;
use App\Models\Service;
use App\Models\Prestation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientAppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $patient;
    protected $hospital;
    protected $service;
    protected $prestation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->hospital = Hospital::factory()->create(['is_active' => true]);
        $this->service = Service::factory()->create([
            'hospital_id' => $this->hospital->id,
            'is_active' => true,
            'consultation_price' => 5000
        ]);
        $this->prestation = Prestation::factory()->create([
            'hospital_id' => $this->hospital->id,
            'category' => 'consultation',
            'is_active' => true,
            'price' => 3000
        ]);

        // Create patient
        $this->patient = Patient::factory()->create([
            'hospital_id' => $this->hospital->id,
            'password' => bcrypt('password'),
            'is_active' => true
        ]);
    }

    /** @test */
    public function patient_can_book_appointment_with_service()
    {
        $this->actingAs($this->patient, 'patients');

        $appointmentData = [
            'consultation_type' => 'hospital',
            'hospital_id' => $this->hospital->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
            'service_or_prestation_id' => $this->service->id,
            'reason' => 'Test consultation',
            'notes' => 'Test notes'
        ];

        $response = $this->post(route('patient.book-appointment.store'), $appointmentData);

        $response->assertRedirect(route('patient.appointments'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $this->patient->id,
            'service_id' => $this->service->id,
            'hospital_id' => $this->hospital->id,
            'consultation_type' => 'hospital',
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function patient_can_book_appointment_with_prestation()
    {
        $this->actingAs($this->patient, 'patients');

        $appointmentData = [
            'consultation_type' => 'hospital',
            'hospital_id' => $this->hospital->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '14:00',
            'service_or_prestation_id' => $this->prestation->id,
            'reason' => 'Test prestation consultation',
            'notes' => 'Test notes for prestation'
        ];

        $response = $this->post(route('patient.book-appointment.store'), $appointmentData);

        $response->assertRedirect(route('patient.appointments'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $this->patient->id,
            'service_id' => null, // Should be null for prestations
            'hospital_id' => $this->hospital->id,
            'consultation_type' => 'hospital',
            'status' => 'pending'
        ]);

        // Check that prestation is attached to appointment
        $appointment = \App\Models\Appointment::where('patient_id', $this->patient->id)->first();
        $this->assertTrue($appointment->prestations->contains($this->prestation->id));
    }

    /** @test */
    public function validation_fails_with_invalid_service_or_prestation_id()
    {
        $this->actingAs($this->patient, 'patients');

        $appointmentData = [
            'consultation_type' => 'hospital',
            'hospital_id' => $this->hospital->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
            'service_or_prestation_id' => 99999, // Non-existent ID
            'reason' => 'Test consultation',
            'notes' => 'Test notes'
        ];

        $response = $this->post(route('patient.book-appointment.store'), $appointmentData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('service_or_prestation_id');
    }

    /** @test */
    public function patient_can_book_home_visit_appointment()
    {
        $this->actingAs($this->patient, 'patients');

        $appointmentData = [
            'consultation_type' => 'home',
            'hospital_id' => $this->hospital->id,
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '09:00',
            'service_or_prestation_id' => $this->service->id,
            'reason' => 'Home visit consultation',
            'notes' => 'Home visit notes',
            'home_address' => '123 Test Street, Test City'
        ];

        $response = $this->post(route('patient.book-appointment.store'), $appointmentData);

        $response->assertRedirect(route('patient.appointments'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $this->patient->id,
            'service_id' => $this->service->id,
            'hospital_id' => $this->hospital->id,
            'consultation_type' => 'home',
            'home_address' => '123 Test Street, Test City',
            'status' => 'pending'
        ]);
    }
}
