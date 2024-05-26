<?php

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourListTest extends TestCase
{

    use RefreshDatabase;

    public function test_tours_list_by_travel_slug_returns_correct_tours()
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create(['travel_id' => $travel->id]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'id' => $tour->id
        ]);
    }

    public function test_tour_price_is_shown_correctly()
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45,
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment([
            'price' => '123.45'
        ]);
    }

    /**
     * Illuminate\Testing\TestResponse::assertJsonCount(): Argument #1 ($count) must 
     * be of type int, null given, called in C:\Users\altho\Documents\GitHub\mentorship
     * \tests\Feature\TourListTest.php on line 59
     * require PHPUNIT_COMPOSER_INSTALL;
     * PHPUnit\TextUI\Command::main();
     * @return void
     */
    public function test_tours_list_returns_pagination()
    {
        // $toursPerPage = config('app.paginationPerPage.tours');

        // $travel = Travel::factory()->create();
        // Tour::factory($toursPerPage + 1)->create([
        //     'travel_id' => $travel->id,
        // ]);

        // $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        // $response->assertStatus(200);
        // $response->assertJsonCount($toursPerPage, 'data');
        // $response->assertJsonPath([
        //     'meta.current_page' => 1
        // ]);

        $travel = Travel::factory()->create();
        Tour::factory(16)->create([
            'travel_id' => $travel->id,
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }
}
