<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Patient;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $hospital;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un hôpital de test
        $this->hospital = Hospital::factory()->create();

        // Créer un utilisateur admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'hospital_id' => $this->hospital->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function admin_can_create_patient()
    {
        $this->actingAs($this->admin)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        $patientData = [
            'name' => 'do',
            'first_name' => 'nunu',
            'dob' => '1990-01-01',
            'gender' => 'M',
            'phone' => '+225 07 00 00 00',
            'email' => 'nunu.do@example.com',
            'address' => '123 Test Street',
            'city' => 'Abidjan',
            'postal_code' => '00225',
            'blood_group' => 'A+',
            'allergies' => 'Pénicilline, Aspirine',
            'medical_history' => 'Antécédents médicaux de test',
            'emergency_contact_name' => 'Contact Urgence',
            'emergency_contact_phone' => '+225 07 00 00 01',
        ];

        $response = $this->post(route('patients.store'), $patientData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Vérifier que le patient a été créé
        $this->assertDatabaseHas('patients', [
            'name' => 'do',
            'first_name' => 'nunu',
            'phone' => '+225 07 00 00 00',
            'email' => 'nunu.do@example.com',
        ]);

        // Vérifier que l'IPU a été généré
        $patient = Patient::where('name', 'do')->where('first_name', 'nunu')->first();
        $this->assertNotNull($patient->ipu);
        $this->assertStringStartsWith('PAT', $patient->ipu);

        // Vérifier que les allergies ont été converties en array
        $this->assertIsArray($patient->allergies);
        $this->assertContains('Pénicilline', $patient->allergies);
        $this->assertContains('Aspirine', $patient->allergies);
    }

    /** @test */
    public function patient_creation_validates_required_fields()
    {
        $this->actingAs($this->admin)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        $response = $this->post(route('patients.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'first_name', 'phone']);
    }

    /** @test */
    public function patient_creation_handles_empty_allergies()
    {
        $this->actingAs($this->admin)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        $patientData = [
            'name' => 'Test',
            'first_name' => 'Patient',
            'phone' => '+225 07 00 00 00',
            'allergies' => '', // Allergies vides
        ];

        $response = $this->post(route('patients.store'), $patientData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $patient = Patient::where('name', 'Test')->where('first_name', 'Patient')->first();
        $this->assertNull($patient->allergies);
    }

    /** @test */
    public function admin_can_edit_patient_with_hospital_field()
    {
        $this->actingAs($this->admin)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        // Créer un patient existant
        $patient = Patient::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);

        // Créer un autre hôpital
        $otherHospital = Hospital::factory()->create();

        // Données de mise à jour
        $updateData = [
            'name' => 'Updated Name',
            'first_name' => 'Updated First Name',
            'dob' => '1995-05-05',
            'gender' => 'F',
            'hospital_id' => $otherHospital->id,
            'phone' => '+225 07 11 11 11',
            'email' => 'updated@example.com',
        ];

        $response = $this->put(route('patients.update', $patient), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Vérifier que le patient a été mis à jour
        $patient->refresh();
        $this->assertEquals('Updated Name', $patient->name);
        $this->assertEquals('Updated First Name', $patient->first_name);
        $this->assertEquals($otherHospital->id, $patient->hospital_id);
    }

    /** @test */
    public function edit_form_includes_hospital_field()
    {
        $this->actingAs($this->admin);

        $patient = Patient::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);

        $response = $this->get(route('patients.edit', $patient));

        $response->assertStatus(200);
        $response->assertSee('Hôpital');
        $response->assertSee('Sélectionner...');
        $response->assertSee($this->hospital->name);
    }

    /** @test */
    public function hospital_field_validation_works()
    {
        $this->actingAs($this->admin)->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        $patient = Patient::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);

        // Essayer de mettre à jour avec un hospital_id invalide
        $updateData = [
            'name' => 'Test',
            'first_name' => 'Patient',
            'dob' => '1990-01-01',
            'gender' => 'M',
            'hospital_id' => 99999, // ID inexistant
        ];

        $response = $this->put(route('patients.update', $patient), $updateData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('hospital_id');
    }
}
