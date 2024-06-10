<?php

namespace App\Http\Controllers;

use App\Models\House_Details;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
class RentHouse extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id',
            'rent_address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'uni_identifier' => 'required|unique:house_details',
            'prefered_occupants' => 'required',
            'type_of_house' => 'required',
            'description' => 'required',
            'rent_fee' => 'required|numeric',
            'number_of_rooms' => 'required|integer|min:1',
            'amenities' => 'required|string',
            'num_bedrooms' => 'required|integer',
            'num_toilets' => 'required|integer',
            'contract_status' => 'required',
        ]);
    
        try {
            // Create a new HouseDetail instance
            $houseDetail = new House_Details();
            $houseDetail->user_id = $request->user_id;
            $houseDetail->rent_address= $request->rent_address;
            $houseDetail->latitude = $request->latitude;
            $houseDetail->longitude = $request->longitude;
            $houseDetail->uni_identifier = $request->uni_identifier;
            $houseDetail->prefered_occupants = $request->prefered_occupants;
            $houseDetail->type_of_house = $request->type_of_house;
            $houseDetail->description = $request->description;
            $houseDetail->rent_fee = $request->rent_fee;
            $houseDetail->number_of_rooms = $request->number_of_rooms;
            $houseDetail->amenities = $request->amenities;
            $houseDetail->num_bedrooms = $request->num_bedrooms;
            $houseDetail->num_toilets = $request->num_toilets;
            $houseDetail->contract_status = $request->contract_status;
    
            // Save the house detail
            $houseDetail->save();
    
            $images = $request->file('images');
            $imagePaths = [];
    
            foreach ($images as $image) {
                $path = $image->store('uploads', 'public');
                $imagePaths[] = $path;
    
                // Save image path to database
                Image::create([
                    
                    'imageable_id' => $houseDetail->id,
                    'imageable_type' => House_Details::class,
                    'path' => $path,
                ]);
            }
    
            // Return a success response with the created house detail
            return response()->json(['message' => 'House detail created successfully', 'house_detail' => $houseDetail], 201);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['message' => 'An error occurred while creating the house detail', 'error' => $e->getMessage()], 500);
        }
    }
    

        // Method to fetch rent houses by user ID
        public function getRentHousesByUser($userId)
        {
            try {
                // Query the database to retrieve rent houses associated with the user ID
                $rentHouses = House_Details::where('user_id', $userId)->get();
    
                // Return the rent houses as a JSON response
                return response()->json($rentHouses);
            } catch (\Exception $e) {
                // Handle any exceptions
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
        public function getRentHousesById($houseId)
        {
            try {
                // Query the database to retrieve rent houses associated with the user ID
                $rentHouseDetail = House_Details::where('id', $houseId)->with('images')->get();
                $rentHouseDetail->each(function ($house) {
                    $house->images->each(function ($image) {
                        $image->url = asset('storage/' .$image->path);
                    });
                });
                // Return the rent houses as a JSON response
                return response()->json($rentHouseDetail);
            } catch (\Exception $e) {
                // Handle any exceptions
                return response()->json(['error' => $e->getMessage()], 500);
            }
    
        }

        public function findHouseById( $houseId)
        {
            try {
                // Query the database to retrieve rent houses associated with the user ID
                $rentHouseDetail = House_Details::where('id', $houseId)->get();
    
                // Return the rent houses as a JSON response
                return response()->json($rentHouseDetail);
            } catch (\Exception $e) {
                // Handle any exceptions
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

    //     public function destroy($id)
    // {
    //     try {
    //         // Find the rent house by ID
    //         $rentHouse = House_Details::findOrFail($id);

    //         // Delete the rent house
    //         $rentHouse->delete();

    //         // Return a success response
    //         return response()->json(['message' => 'Rent house deleted successfully'], 204);
    //     } catch (\Exception $e) {
    //         // Return an error response if something goes wrong
    //         return response()->json(['message' => 'Failed to delete rent house', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function destroy($id)
{
    try {
        // Find the rent house by ID
        $rentHouse = House_Details::findOrFail($id);

        // Check the contract status
        if ($rentHouse->contract_status === 'Active') {
            // Return a response indicating that active contracts cannot be deleted
            return response()->json(['message' => 'Active contracts cannot be deleted'], 400);
        }

        // Delete the rent house if the status is 'Inactive' or 'Ended'
        $rentHouse->delete();

        // Return a success response
        return response()->json(['message' => 'Rent house deleted successfully'], 204);
    } catch (\Exception $e) {
        // Return an error response if something goes wrong
        return response()->json(['message' => 'Failed to delete rent house', 'error' => $e->getMessage()], 500);
    }
}


    public function update(Request $request, $id)
    {
        // Validate the request data if needed
    
        // Find the rent house by ID
        $rentHouse = House_Details::findOrFail($id);
    
        // Update the rent house attributes with the fields present in the request
        $rentHouse->fill($request->all())->save();
    
        // Return a response indicating success
        return response()->json(['message' => 'Rent house updated successfully'], 200);
    }
    
    public function list(Request $request) {

        try{
            // $rentHouse = House_Details::all();
            $rentHouse = House_Details::where('available', true)->with('images')->get();
            // $rentHouse = House_Details::with('images')->get();

            $rentHouse->each(function ($house) {
                $house->images->each(function ($image) {
                    $image->url = asset('storage/' . $image->path);
                });
            });
            return response()->json($rentHouse);
        }
        catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    public function compareIdentifier(Request $request)
    {
      try {
        // Get the university identifier and house ID from the request
        $uniIdentifier = $request->input('uniIdentifier');
        $houseId = $request->input('houseId');
    
        // Log retrieved values
        error_log("Received uniIdentifier: $uniIdentifier, houseId: $houseId");
    
        // Check if the university identifier belongs to the specified house ID
        $houseDetail = DB::table('house_details')
          ->where('uni_identifier', $uniIdentifier)
          ->where('id', $houseId)
          ->first();
    
        // If the house detail exists, return a success response
        if ($houseDetail) {
        //  return response()->json(['isMatch' => true]);
          return response()->json(['message' => 'Applied Succesfully!']);
          
          
        } else {
          // Log missing house details
          error_log("University identifier not found for house ID $houseId");
          return response()->json(['error' => 'University identifier does not match the specified house ID'], 400);
        }
      } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }
    
    public function getUniIdentifier($houseId)
    {
        $house = House_Details::findOrFail($houseId);

        // Assuming 'uni_identifier' is a field in your House_Details model
        return response()->json(['uni_identifier' => $house->uni_identifier], 200);
    }
    
}
