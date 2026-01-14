<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Service;
use App\Models\Patient;
use App\Models\Room;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $hospital;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un hôpital de test
        $this->hospital = Hospital::factory()->create([
            'name' => 'Hôpital Test',
            'slug' => 'test-hospital',
            'is_active' => true
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
    public function admin_dashboard_shows_dynamic_doctor_count()
    {
        // Créer plusieurs médecins actifs de différents types
        User::factory()->create([
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        User::factory()->create([
            'role' => 'internal_doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        User::factory()->create([
            'role' => 'external_doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin inactif (ne devrait pas être compté)
        User::factory()->create([
            'role' => 'doctor',
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un médecin d'un autre hôpital (ne devrait pas être compté)
        $otherHospital = Hospital::factory()->create(['name' => 'Other Hospital']);
        User::factory()->create([
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $otherHospital->id
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que le nombre de médecins actifs est 3
        $response->assertViewHas('totalDoctors', 3);
    }

    /** @test */
    public function admin_dashboard_shows_dynamic_patient_count()
    {
        // Créer des patients actifs
        Patient::factory()->count(5)->create([
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer des patients inactifs (ne devraient pas être comptés)
        Patient::factory()->count(2)->create([
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer des patients d'un autre hôpital (ne devraient pas être comptés)
        $otherHospital = Hospital::factory()->create(['name' => 'Other Hospital']);
        Patient::factory()->count(3)->create([
            'is_active' => true,
            'hospital_id' => $otherHospital->id
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que le nombre de patients actifs est 5
        $response->assertViewHas('totalPatients', 5);
    }

    /** @test */
    public function admin_dashboard_shows_dynamic_service_count()
    {
        // Créer des services actifs
        Service::factory()->count(4)->create([
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer des services inactifs (ne devraient pas être comptés)
        Service::factory()->count(2)->create([
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer des services d'un autre hôpital (ne devraient pas être comptés)
        $otherHospital = Hospital::factory()->create(['name' => 'Other Hospital']);
        Service::factory()->count(3)->create([
            'is_active' => true,
            'hospital_id' => $otherHospital->id
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que le nombre de services actifs est 4
        $response->assertViewHas('totalServices', 4);
    }

    /** @test */
    public function admin_dashboard_shows_dynamic_bed_occupancy()
    {
        // Créer des chambres
        Room::factory()->count(10)->create([
            'status' => 'available',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        Room::factory()->count(5)->create([
            'status' => 'occupied',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        Room::factory()->count(2)->create([
            'status' => 'cleaning',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Chambre inactive (ne devrait pas être comptée)
        Room::factory()->create([
            'status' => 'available',
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        // Chambre d'un autre hôpital (ne devrait pas être comptée)
        $otherHospital = Hospital::factory()->create(['name' => 'Other Hospital']);
        Room::factory()->create([
            'status' => 'occupied',
            'is_active' => true,
            'hospital_id' => $otherHospital->id
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier les statistiques des lits
        $response->assertViewHas('available_beds', 10); // chambres disponibles
        $response->assertViewHas('total_beds', 17); // 10 available + 5 occupied + 2 cleaning = 17
        $response->assertViewHas('occupancyRate', 29.4); // (5/17)*100 ≈ 29.4
    }
}
