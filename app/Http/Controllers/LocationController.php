<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LocationService;
use App\Models\Location; 
use App\Http\Requests\CalculateDistanceRequest;
use App\Http\Requests\StoreLocationRequest;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    protected $locationService;
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }
    
    public function store(StoreLocationRequest $request)
    {
        $validatedData = $request->validated();
        $location = Location::create($validatedData);
    
        return response()->json([
            'res' => true,
            'message' => 'Location added successfully',
            'data' => $location
        ], 201);
    }

    public function index()
    {
        $locations = Location::all();
    
        return response()->json([
            'res' => true,
            'message' => '',
            'data' => $locations
        ]);
    }
    

    public function show($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json([
                'res' => false,
                'message' => 'Location not found'
            ], 404);
        }
        return response()->json([
            'res' => true,
            'message' => 'Location found successfully',
            'data' => $location
        ]);
    }
    

    public function update(StoreLocationRequest $request, $id)
    {
        $validatedData = $request->validated();
        $location = Location::find($id);
        if (!$location) {
            return response()->json([
                'res' => false,
                'message' => 'Location not found'
            ], 404);
        }
    
        $location->update($validatedData);
        return response()->json([
            'res' => true,
            'message' => 'Location updated successfully',
            'data' => $location
        ]);
    }
    

    public function destroy($id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json([
                'res' => false,
                'message' => 'Location not found'
            ], 404);
        }
        
        $location->delete();
        return response()->json([
            'res' => true,
            'message' => 'Location deleted successfully'
        ]);
    }
    
    public function multiLocationCreate(Request $request) {
        $success = false;
        $message = "0 locations have been added";
        
        $validated = $request->validate([
            'locations' => 'required|array',
            'locations.*.name' => 'required|string',
            'locations.*.latitude' => 'required|numeric',
            'locations.*.longitude' => 'required|numeric',
            'locations.*.color' => 'required|string',
        ]);
    
        $locations = $validated['locations'];
        $createdLocations = [];
    
        if ($locations) {
            foreach ($locations as $locationData) {
                $createdLocations[] = Location::create($locationData);
            }
            $success = true;
            $message = count($createdLocations) . " locations have been added.";
        }
        return response()->json([
            'response' => $success,
            'message' => $message,
            'data' => $createdLocations
        ]);
    }

    // Girilen koordinatlar arası mesafe hesaplama
    public function calculateDistanceWithCoordinates(CalculateDistanceRequest $request)
    {
        $validatedData = $request->validated();
        $userLat = $validatedData['latitude'];
        $userLon = $validatedData['longitude'];
    
        $locations = Location::all();
    
        $distances = [];
    
        foreach ($locations as $location) {
            $distance = $this->locationService->calculateDistance(
                $userLat, $userLon, $location->latitude, $location->longitude
            );
    
            $distances[] = [
                'location_id' => $location->id,
                'location_name' => $location->name,
                'distance' => $distance
            ];
        }
    
        usort($distances, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
    
        return response()->json([
            'res' => true,
            'message' => "Locations sorted by distance",
            'data' => $distances
        ]);
    }
    
    

    // Tablolarda kayıtlı olan konumlardan seçilenler arası mesafe hesaplama   
    public function calculateDistanceWithID($departureID, $destinationID){
        if ($departureID <= 0 || $destinationID <= 0) {
            return response()->json([
                'res'=>false,
                'message' => "The entered IDs must be greater than zero.",
                'data'=>""
            ],400);
        }
        $departure = Location::find($departureID);
        $destination = Location::find($destinationID);
        if(!$departure){
            return response()->json([
                'res'=>false,
                'message' => "There is no location with ID ".$departureID." in our records.",
                'data'=>""
            ],400);
        }
        if(!$destination){
            return response()->json([
                'res'=>false,
                'message' => "There is no location with ID ".$departureID." in our records.",
                'data'=>""
            ],400);

        }
        $distance =$this->locationService->calculateDistance($departure->latitude,$departure->longitude,$destination->latitude,$destination->longitude);

        return response()->json([
            'res'=>true,
            'message' => "The distance between ".$departure->name." and ".$destination->name." is as follows.",
            'data'=>$distance
        ]);
    }


    
}
