<?php

namespace Tests\Feature\Http\Controllers\Pet;

use Database\Seeders\PetsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FindPetByIdControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_pet(): void
    {
        $this->seed(PetsSeeder::class);

        $response = $this->get('/api/pet/288');

        $response->assertStatus(200);
    }

    public function test_pet_not_found(): void
    {
        $response = $this->get('/api/pet/123');

        $response->assertStatus(404);
    }

    public function test_validation_error_when_wrong_id_format(): void
    {
        $response = $this->get('/api/pet/'.uniqid());

        $response->assertStatus(422);
    }
}
