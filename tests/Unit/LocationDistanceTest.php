<?php

namespace Tests\Unit;

use App\Models\Location;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class LocationDistanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_locations_sorted_by_distance()
    {
        $userLat = 40.7128;
        $userLon = -74.0060;

        $location1 = Location::create([
            'name' => 'Location 1',
            'latitude' => 40.7138,
            'longitude' => -74.0080,
            'color' => 'red',
        ]);

        $location2 = Location::create([
            'name' => 'Location 2',
            'latitude' => 41.7128,
            'longitude' => -73.0060,
            'color' => 'green',
        ]);

        $location3 = Location::create([
            'name' => 'Location 3',
            'latitude' => 40.7158,
            'longitude' => -74.0040,
            'color' => 'blue',
        ]);

        $mockLocationService = Mockery::mock('App\Services\LocationService');
        $mockLocationService->shouldReceive('calculateDistance')
                            ->andReturnUsing(function($lat1, $lon1, $lat2, $lon2) use ($userLat, $userLon) {
                                return sqrt(pow($lat1 - $lat2, 2) + pow($lon1 - $lon2, 2));
                            });

        $this->app->instance('App\Services\LocationService', $mockLocationService);
        
        $response = $this->json('POST', '/api/locations/calculate-distance', [
            'latitude' => $userLat,
            'longitude' => $userLon,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'location_id' => $location1->id,
                     'location_name' => 'Location 1',
                 ])
                 ->assertJsonFragment([
                     'location_id' => $location3->id,
                     'location_name' => 'Location 3',
                 ]);

        $distances = $response->json('data');
        $this->assertTrue($distances[0]['distance'] < $distances[1]['distance']);
        $this->assertTrue($distances[1]['distance'] < $distances[2]['distance']);
    }
}
