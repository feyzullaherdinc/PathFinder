<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example for creating location.
     *
     * @return void
     */
    public function test_location_can_be_created()
    {
        $locationData = [
            'name' => 'Test Location',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'color' => 'red',
        ];

        $response = $this->json('POST', '/api/locations', $locationData);

        $response->assertStatus(201)
                 ->assertJson([
                     'res' => true,
                     'message' => 'Location added successfully',
                 ]);

        $this->assertDatabaseHas('locations', [
            'name' => 'Test Location',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'color' => 'red',
        ]);
    }

    /**
     * A basic test example for updating location.
     *
     * @return void
     */
    public function test_location_can_be_updated()
    {
        $location = Location::create([
            'name' => 'Old Location',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'color' => 'blue',
        ]);

        $updatedData = [
            'name' => 'Updated Location',
            'latitude' => 41.7128,
            'longitude' => -73.0060,
            'color' => 'green',
        ];

        $response = $this->json('PUT', "/api/locations/{$location->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJson([
                     'res' => true,
                     'message' => 'Location updated successfully',
                 ]);

        $this->assertDatabaseHas('locations', [
            'name' => 'Updated Location',
            'latitude' => 41.7128,
            'longitude' => -73.0060,
            'color' => 'green',
        ]);
    }

    /**
     * A basic test example for listing locations.
     *
     * @return void
     */
    public function test_locations_can_be_listed()
    {
        $location1 = Location::create([
            'name' => 'Location 1',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'color' => 'red',
        ]);

        $location2 = Location::create([
            'name' => 'Location 2',
            'latitude' => 41.7128,
            'longitude' => -73.0060,
            'color' => 'green',
        ]);

        $response = $this->json('GET', '/api/locations');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Location 1',
                     'latitude' => 40.7128,
                     'longitude' => -74.0060,
                     'color' => 'red',
                 ])
                 ->assertJsonFragment([
                     'name' => 'Location 2',
                     'latitude' => 41.7128,
                     'longitude' => -73.0060,
                     'color' => 'green',
                 ]);
    }

    /**
     * A basic test example for listing location by ID.
     *
     * @return void
     */
    public function test_location_can_be_listed_by_id()
    {
        $location = Location::create([
            'name' => 'Location 1',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'color' => 'red',
        ]);

        $response = $this->json('GET', "/api/locations/{$location->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'res' => true,
                     'message' => 'Location found successfully',
                 ])
                 ->assertJsonFragment([
                     'id' => $location->id,
                     'name' => 'Location 1',
                     'latitude' => 40.7128,
                     'longitude' => -74.0060,
                     'color' => 'red',
                 ]);
    }



    

}
