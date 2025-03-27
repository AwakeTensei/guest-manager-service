<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class GuestTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('ALTER SEQUENCE guests_id_seq RESTART WITH 1');
    }

    public function test_create_guest()
    {
        $response = $this->postJson('/api/guests', [
            'first_name' => 'Van',
            'last_name' => 'Ivanov',
            'phone' => '+79624567890',
            'email' => 'van.ivanov@example.ru',
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'Guest created'])
                 ->assertHeader('X-Debug-Time')
                 ->assertHeader('X-Debug-Memory');
    }

    public function test_get_guest()
    {
        $createResponse = $this->postJson('/api/guests', [
            'first_name' => 'Van',
            'last_name' => 'Ivanov',
            'phone' => '+79624567890',
            'email' => 'van.ivanov@example.ru',
        ]);

        $guestId = $createResponse->json('id');
        $response = $this->getJson("/api/guests/{$guestId}");
        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'phone',
                        'email',
                        'country',
                    ]
                ])
                 ->assertHeader('X-Debug-Time')
                 ->assertHeader('X-Debug-Memory');
    }
}

