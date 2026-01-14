<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\Prestation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $hospital;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hospital = Hospital::factory()->create();
        $this->service = Service::factory()->create(['hospital_id' => $this->hospital->id]);
        $this->user = User::factory()->create([
            'hospital_id' => $this->hospital->id,
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function it_can_create_prestation_with_high_price()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'Test Prestation',
            'code' => 'TEST-001',
            'category' => 'consultation',
            'service_id' => $this->service->id,
            'price' => 200000000, // High price that was causing the error
            'description' => 'Test description',
            'payment_timing' => 'before',
            'priority' => 'medium',
            '_token' => csrf_token()
        ];

        $response = $this->post(route('prestations.store'), $data);

        $response->assertRedirect(route('prestations.index'));
        $this->assertDatabaseHas('prestations', [
            'name' => 'Test Prestation',
            'price' => 200000000.00
        ]);
    }

    /** @test */
    public function it_can_update_prestation_with_high_price()
    {
        $this->actingAs($this->user);

        $prestation = Prestation::factory()->create([
            'hospital_id' => $this->hospital->id,
            'service_id' => $this->service->id,
            'price' => 100.00
        ]);

        $data = [
            'name' => 'Updated Prestation',
            'code' => 'UPDATED-001',
            'category' => 'consultation',
            'service_id' => $this->service->id,
            'price' => 200000000, // High price
            'description' => 'Updated description',
            'is_active' => true,
            'requires_payment' => true
        ];

        $response = $this->put(route('prestations.update', $prestation), $data);

        $response->assertRedirect(route('prestations.index'));
        $this->assertDatabaseHas('prestations', [
            'id' => $prestation->id,
            'price' => 200000000.00
        ]);
    }
}
