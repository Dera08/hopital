<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HospitalCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function hospital_creation_includes_slug_field()
    {
        // Test that hospital creation works with slug field
        $hospital = Hospital::create([
            'name' => 'Test Hospital',
            'slug' => 'test-hospital',
            'address' => '123 Test Street',
            'is_active' => true,
        ]);

        // Assert the hospital was created successfully
        $this->assertDatabaseHas('hospitals', [
            'name' => 'Test Hospital',
            'slug' => 'test-hospital',
            'address' => '123 Test Street',
            'is_active' => true,
        ]);

        // Assert the slug field is not null
        $this->assertNotNull($hospital->slug);
        $this->assertEquals('test-hospital', $hospital->slug);
    }

    /** @test */
    public function hospital_creation_without_slug_fails()
    {
        // This should fail because slug is required
        $this->expectException(\Illuminate\Database\QueryException::class);

        Hospital::create([
            'name' => 'Test Hospital Without Slug',
            'address' => '123 Test Street',
            'is_active' => true,
        ]);
    }
}
