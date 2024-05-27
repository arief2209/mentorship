<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelListTest extends TestCase
{
    // use it coutiously cuz can wipe all the database 
    use RefreshDatabase;
    private $travelEndPoint = '/api/v1/travels';

    public function test_travels_list_returns_paginated_data_correctly()
    {
        Travel::factory(16)->create([
            'is_public' => true
        ]);

        $response = $this->get($this->travelEndPoint);

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_travels_list_shows_only_public_records()
    {
        $publicTravel = Travel::factory()->create([
            'is_public' => true
        ]);
        Travel::factory()->create([
            'is_public' => false
        ]);

        $response = $this->get($this->travelEndPoint);

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', $publicTravel->name);
    }
}
