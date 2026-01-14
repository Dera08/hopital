<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $hospital;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un hôpital de test
        $this->hospital = Hospital::factory()->create([
            'name' => 'Hôpital Saint-Jean',
            'slug' => 'saint-jean',
            'is_active' => true
        ]);

        // Créer un admin pour cet hôpital
        $this->admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@saint-jean.test',
            'role' => 'admin',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('inactiveUsers');
    }

    /** @test */
    public function dashboard_shows_inactive_users_for_admin_hospital()
    {
        // Créer quelques utilisateurs inactifs pour cet hôpital
        $inactiveUser1 = User::factory()->create([
            'name' => 'Dr. Inactive One',
            'email' => 'inactive1@saint-jean.test',
            'role' => 'doctor',
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        $inactiveUser2 = User::factory()->create([
            'name' => 'Nurse Inactive',
            'email' => 'inactive2@saint-jean.test',
            'role' => 'nurse',
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un utilisateur actif (ne devrait pas apparaître)
        $activeUser = User::factory()->create([
            'name' => 'Dr. Active',
            'email' => 'active@saint-jean.test',
            'role' => 'doctor',
            'is_active' => true,
            'hospital_id' => $this->hospital->id
        ]);

        // Créer un utilisateur inactif pour un autre hôpital (ne devrait pas apparaître)
        $otherHospital = Hospital::factory()->create(['name' => 'Other Hospital']);
        $otherInactiveUser = User::factory()->create([
            'name' => 'Dr. Other Hospital',
            'email' => 'other@test.com',
            'role' => 'doctor',
            'is_active' => false,
            'hospital_id' => $otherHospital->id
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que les données sont passées à la vue
        $response->assertViewHas('inactiveUsers', function ($inactiveUsers) use ($inactiveUser1, $inactiveUser2) {
            return $inactiveUsers->contains($inactiveUser1) &&
                   $inactiveUsers->contains($inactiveUser2) &&
                   $inactiveUsers->count() === 2;
        });
    }

    /** @test */
    public function admin_can_toggle_user_status()
    {
        $inactiveUser = User::factory()->create([
            'name' => 'Dr. To Activate',
            'email' => 'toactivate@saint-jean.test',
            'role' => 'doctor',
            'is_active' => false,
            'hospital_id' => $this->hospital->id
        ]);

        $response = $this->actingAs($this->admin)
                        ->patch("/users/{$inactiveUser->id}/toggle-status");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Utilisateur activé.');

        // Vérifier que l'utilisateur est maintenant actif
        $inactiveUser->refresh();
        $this->assertTrue($inactiveUser->is_active);
    }

    /** @test */
    public function inactive_users_show_correct_information()
    {
        $service = Service::factory()->create([
            'name' => 'Cardiologie',
            'hospital_id' => $this->hospital->id
        ]);

        $inactiveUser = User::factory()->create([
            'name' => 'Dr. Cardiologue',
            'email' => 'cardio@saint-jean.test',
            'role' => 'doctor',
            'is_active' => false,
            'hospital_id' => $this->hospital->id,
            'service_id' => $service->id,
            'created_at' => now()->subDays(5)
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');

        $response->assertStatus(200);

        // Vérifier que les informations sont correctement affichées dans la vue
        $inactiveUsers = $response->viewData('inactiveUsers');
        $user = $inactiveUsers->first();

        $this->assertEquals('Dr. Cardiologue', $user->name);
        $this->assertEquals('cardio@saint-jean.test', $user->email);
        $this->assertEquals('doctor', $user->role);
        $this->assertEquals('Cardiologie', $user->service->name);
    }
}
