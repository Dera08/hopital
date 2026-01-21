<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Service;
use App\Models\Prestation;
use App\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un superadmin pour les tests
        $this->superAdmin = SuperAdmin::create([
            'name' => 'Test Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password'),
            'access_code' => '123456',
        ]);
    }

    /** @test */
    public function superadmin_can_get_hospital_details()
    {
        // Créer un hôpital avec des données
        $hospital = Hospital::create([
            'name' => 'Test Hospital',
            'slug' => 'test-hospital',
            'address' => '123 Test Street',
            'is_active' => true,
        ]);

        // Créer un service pour l'hôpital
        $service = Service::create([
            'name' => 'Cardiology',
            'code' => 'CARD',
            'hospital_id' => $hospital->id,
            'description' => 'Heart care',
        ]);

        // Créer un utilisateur pour l'hôpital
        $user = User::create([
            'name' => 'Dr. Test',
            'email' => 'doctor@test.com',
            'password' => bcrypt('password'),
            'role' => 'doctor',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id,
            'is_active' => true,
        ]);

        // Créer une prestation pour l'hôpital
        $prestation = Prestation::create([
            'name' => 'Consultation',
            'code' => 'CONSULT',
            'hospital_id' => $hospital->id,
            'service_id' => $service->id,
            'price' => 5000,
            'category' => 'consultation',
            'is_active' => true,
        ]);

        // Authentifier le superadmin
        Auth::guard('superadmin')->login($this->superAdmin);

        // Faire la requête API
        $response = $this->getJson("/admin-system/hospitals/{$hospital->id}/details");

        // Vérifier la réponse
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'stats' => [
                        'total_users' => 1,
                        'total_services' => 1,
                        'total_prestations' => 1,
                        'active_users' => 1,
                    ]
                ]);

        // Vérifier que les données sont présentes
        $responseData = $response->json();
        $this->assertCount(1, $responseData['hospital']['users']);
        $this->assertCount(1, $responseData['hospital']['services']);
        $this->assertCount(1, $responseData['hospital']['prestations']);

        // Vérifier les détails des utilisateurs
        $this->assertEquals('Dr. Test', $responseData['hospital']['users'][0]['name']);
        $this->assertEquals('doctor', $responseData['hospital']['users'][0]['role']);

        // Vérifier les détails des services
        $this->assertEquals('Cardiology', $responseData['hospital']['services'][0]['name']);
        $this->assertEquals('CARD', $responseData['hospital']['services'][0]['code']);

        // Vérifier les détails des prestations
        $this->assertEquals('Consultation', $responseData['hospital']['prestations'][0]['name']);
        $this->assertEquals(5000, $responseData['hospital']['prestations'][0]['price']);
    }

    /** @test */
    public function superadmin_cannot_get_nonexistent_hospital_details()
    {
        // Authentifier le superadmin
        Auth::guard('superadmin')->login($this->superAdmin);

        // Faire la requête API pour un hôpital inexistant
        $response = $this->getJson("/admin-system/hospitals/999/details");

        // Vérifier que ça retourne une erreur 404
        $response->assertStatus(404);
    }
}
