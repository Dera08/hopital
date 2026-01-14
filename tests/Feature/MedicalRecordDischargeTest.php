<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Bed;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\PatientVital;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRecordDischargeTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_discharge_patient()
    {
        // Create necessary data
        $hospital = Hospital::factory()->create();
        $service = Service::factory()->create(['hospital_id' => $hospital->id]);
        $doctor = User::factory()->create([
            'hospital_id' => $hospital->id,
            'service_id' => $service->id,
            'role' => 'doctor'
        ]);
        $patient = Patient::factory()->create(['hospital_id' => $hospital->id]);
        $room = Room::factory()->create([
            'hospital_id' => $hospital->id,
            'service_id' => $service->id
        ]);
        $bed = Bed::factory()->create([
            'room_id' => $room->id,
            'is_available' => true
        ]);
        $patientVital = PatientVital::factory()->create([
            'hospital_id' => $hospital->id,
            'service_id' => $service->id,
            'patient_ipu' => $patient->ipu,
            'status' => 'admitted'
        ]);
        $admission = Admission::factory()->create([
            'hospital_id' => $hospital->id,
            'patient_id' => $patient->id,
            'room_id' => $room->id,
            'bed_id' => $bed->id,
            'doctor_id' => $doctor->id,
            'status' => 'active'
        ]);

        // Act as the doctor
        $this->actingAs($doctor);

        // Call the discharge endpoint
        $response = $this->post("/medical-records/{$admission->id}/discharge");

        // Assert the response is a redirect
        $response->assertRedirect(route('medecin.dashboard'));

        // Assert the admission is updated
        $admission->refresh();
        $this->assertEquals('discharged', $admission->status);
        $this->assertNotNull($admission->discharge_date);

        // Assert the room is available
        $room->refresh();
        $this->assertEquals('available', $room->status);

        // Assert the bed is available
        $bed->refresh();
        $this->assertTrue($bed->is_available);

        // Assert the patient vital is archived
        $patientVital->refresh();
        $this->assertEquals('archived', $patientVital->status);
    }
}
