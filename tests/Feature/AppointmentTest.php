<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function nurse_can_prepare_appointment_and_send_dossier()
    {
        // Create a nurse user
        $hospital = \App\Models\Hospital::factory()->create();
        $service = \App\Models\Service::factory()->create(['hospital_id' => $hospital->id]);
        $nurse = User::factory()->create([
            'role' => 'nurse',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id
        ]);
        $patient = Patient::factory()->create([
            'ipu' => 'PAT-001',
            'hospital_id' => $hospital->id
        ]);
        $doctor = User::factory()->create([
            'role' => 'doctor',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id
        ]);

        // Create an appointment
        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'appointment_datetime' => now()->toDateString() . ' 10:00:00',
        ]);

        // Act as the nurse
        $this->actingAs($nurse);

        // Data for sending dossier
        $data = [
            'patient_name' => $patient->name,
            'patient_ipu' => $patient->ipu,
            'urgency' => 'normale',
            'reason' => 'Consultation générale',
            'temperature' => '37.5',
            'pulse' => '80',
            'blood_pressure' => '120/80',
        ];

        // Send the dossier
        $response = $this->post(route('nurse.send'), $data);

        // Assert that the response redirects back (302)
        $response->assertStatus(302);

        // Assert that the appointment status is updated to 'prepared'
        $appointment->refresh();
        $this->assertEquals('prepared', $appointment->status);
    }

    /** @test */
    public function prepared_appointments_do_not_appear_in_doctor_index()
    {
        // Create a doctor user
        $hospital = \App\Models\Hospital::factory()->create();
        $service = \App\Models\Service::factory()->create(['hospital_id' => $hospital->id]);
        $doctor = User::factory()->create([
            'role' => 'doctor',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id
        ]);
        $preparedPatient = Patient::factory()->create(['hospital_id' => $hospital->id]);
        $scheduledPatient = Patient::factory()->create(['hospital_id' => $hospital->id]);

        // Create a prepared appointment
        $preparedAppointment = Appointment::factory()->create([
            'patient_id' => $preparedPatient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'status' => 'prepared',
            'appointment_datetime' => now()->addDay(),
        ]);

        // Create a scheduled appointment
        $scheduledAppointment = Appointment::factory()->create([
            'patient_id' => $scheduledPatient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'status' => 'scheduled',
            'appointment_datetime' => now()->addDay(),
        ]);

        // Act as the doctor
        $this->actingAs($doctor);

        // Access the appointments index
        $response = $this->get(route('appointments.index'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the prepared appointment is not in the response
        $response->assertDontSee($preparedAppointment->patient->name);

        // Assert that the scheduled appointment is in the response
        $response->assertSee($scheduledAppointment->patient->name);
    }

    /** @test */
    public function prepared_appointments_do_not_appear_in_nurse_dashboard()
    {
        // Create a nurse user
        $hospital = \App\Models\Hospital::factory()->create();
        $service = \App\Models\Service::factory()->create(['hospital_id' => $hospital->id]);
        $nurse = User::factory()->create([
            'role' => 'nurse',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id
        ]);
        $patient = Patient::factory()->create(['hospital_id' => $hospital->id]);
        $doctor = User::factory()->create([
            'role' => 'doctor',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id
        ]);

        // Create a prepared appointment
        $preparedAppointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'status' => 'prepared',
            'appointment_datetime' => now()->toDateString() . ' 10:00:00',
        ]);

        // Create a scheduled appointment
        $scheduledAppointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'status' => 'scheduled',
            'appointment_datetime' => now()->toDateString() . ' 11:00:00',
        ]);

        // Act as the nurse
        $this->actingAs($nurse);

        // Access the nurse dashboard
        $response = $this->get(route('nurse.dashboard'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the prepared appointment is not in the response
        $response->assertDontSee($preparedAppointment->patient->name);

        // Assert that the scheduled appointment is in the response
        $response->assertSee($scheduledAppointment->patient->name);
    }
}
