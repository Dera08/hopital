<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\Appointment;
use App\Models\ClinicalObservation;
use App\Models\MedicalRecord;
use App\Models\Service;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardActivitiesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $hospital;
    protected $service;
    protected $room;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un hôpital de test
        $this->hospital = Hospital::factory()->create([
            'name' => 'Hôpital Test',
            'slug' => 'test-hospital',
            'is_active' => true
        ]);

        // Créer un service
        $this->service = Service::factory()->create([
            'name' => 'Cardiologie',
            'hospital_id' => $this->hospital->id,
            'is_active' => true
        ]);

        // Créer une chambre
        $this->room = Room::factory()->create([
            'room_number' => '101',
            'status' => 'available',
            'is_active' => true,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id
        ]);

        // Créer un admin pour cet hôpital
        $this->admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.test',
            'role' => 'admin',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);
    }

    /** @test */
    public function admin_dashboard_shows_recent_admissions()
    {
        // Créer un patient
        $patient = Patient::factory()->create([
            'name' => 'M. Test Patient',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin
        $doctor = User::factory()->create([
            'name' => 'Dr. Test Doctor',
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id
        ]);

        // Créer une admission récente
        Admission::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'room_id' => $this->room->id,
            'hospital_id' => $this->hospital->id,
            'admission_date' => now()->subMinutes(10),
            'status' => 'active'
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que l'admission apparaît dans les activités récentes
        $response->assertViewHas('recentActivities', function ($activities) {
            return $activities->contains(function ($activity) {
                return str_contains($activity['message'], 'Patient M. Test Patient admis');
            });
        });
    }

    /** @test */
    public function admin_dashboard_shows_recent_appointments()
    {
        // Créer un patient
        $patient = Patient::factory()->create([
            'name' => 'Mme. Test Patient',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin
        $doctor = User::factory()->create([
            'name' => 'Dr. Appointment Doctor',
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id
        ]);

        // Créer un rendez-vous terminé récemment
        Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id,
            'appointment_datetime' => now()->subMinutes(15),
            'status' => 'completed'
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que le rendez-vous apparaît dans les activités récentes
        $response->assertViewHas('recentActivities', function ($activities) {
            return $activities->contains(function ($activity) {
                return str_contains($activity['message'], 'Rendez-vous terminé avec Mme. Test Patient');
            });
        });
    }

    /** @test */
    public function admin_dashboard_shows_recent_clinical_observations()
    {
        // Créer un patient
        $patient = Patient::factory()->create([
            'name' => 'M. Observation Patient',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin
        $doctor = User::factory()->create([
            'name' => 'Dr. Observation Doctor',
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id
        ]);

        // Créer une observation clinique récente
        ClinicalObservation::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $doctor->id,
            'hospital_id' => $this->hospital->id,
            'type' => 'Tension artérielle',
            'observation_datetime' => now()->subMinutes(20),
            'is_critical' => false
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que l'observation apparaît dans les activités récentes
        $response->assertViewHas('recentActivities', function ($activities) {
            return $activities->contains(function ($activity) {
                return str_contains($activity['message'], 'Observation clinique ajoutée pour M. Observation Patient');
            });
        });
    }

    /** @test */
    public function admin_dashboard_shows_recent_medical_records()
    {
        // Créer un patient
        $patient = Patient::factory()->create([
            'name' => 'M. Record Patient',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin
        $doctor = User::factory()->create([
            'name' => 'Dr. Record Doctor',
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id
        ]);

        // Créer un dossier médical récent
        MedicalRecord::factory()->create([
            'patient_id' => $patient->id,
            'recorded_by' => $doctor->id,
            'hospital_id' => $this->hospital->id,
            'diagnosis' => 'Hypertension',
            'created_at' => now()->subMinutes(25)
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que le dossier médical apparaît dans les activités récentes
        $response->assertViewHas('recentActivities', function ($activities) {
            return $activities->contains(function ($activity) {
                return str_contains($activity['message'], 'Dossier médical mis à jour pour M. Record Patient');
            });
        });
    }

    /** @test */
    public function admin_dashboard_activities_are_sorted_by_time()
    {
        // Créer un patient
        $patient = Patient::factory()->create([
            'name' => 'M. Sorted Patient',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin
        $doctor = User::factory()->create([
            'name' => 'Dr. Sorted Doctor',
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id
        ]);

        // Créer des activités à différents moments
        MedicalRecord::factory()->create([
            'patient_id' => $patient->id,
            'recorded_by' => $doctor->id,
            'hospital_id' => $this->hospital->id,
            'diagnosis' => 'First activity',
            'created_at' => now()->subMinutes(30)
        ]);

        ClinicalObservation::factory()->create([
            'patient_id' => $patient->id,
            'user_id' => $doctor->id,
            'hospital_id' => $this->hospital->id,
            'type' => 'Second activity',
            'observation_datetime' => now()->subMinutes(20),
            'is_critical' => false
        ]);

        Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id,
            'appointment_datetime' => now()->subMinutes(10),
            'status' => 'completed'
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que les activités sont triées par ordre décroissant (plus récent en premier)
        $response->assertViewHas('recentActivities', function ($activities) {
            if ($activities->count() < 3) return false;

            $firstActivity = $activities->first();
            $lastActivity = $activities->last();

            // Le premier élément devrait être le plus récent (rendez-vous)
            // Le dernier élément devrait être le plus ancien (dossier médical)
            return str_contains($firstActivity['message'], 'Rendez-vous terminé') &&
                   str_contains($lastActivity['message'], 'Dossier médical mis à jour');
        });
    }
}
