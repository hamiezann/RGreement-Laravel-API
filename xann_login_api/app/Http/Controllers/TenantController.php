<?php

namespace App\Http\Controllers;

use App\Models\House_Details;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:users,id',
            'tenant_status' => 'required',
            'house_id' => 'required|exists:house_details,id',
            'sign_contract_status' => 'required',
        ]);

        try {
            $tenantDetail = new Tenant();
            $tenantDetail -> fill($request->all());
            $tenantDetail->save();

            return response()->json(['message' => 'Tenant detail added succesfully.'], 201);

        }catch (\Exception $e) {
            return response()->json(['error' => $e ->getMessage()], 500);
        }
    }
    // public function index(Request $request)
    // {
    //     try {
    //         // Fetch tenants with pending status along with the referenced user details
    //         $pendingTenants = Tenant::with('user')->where('tenant_status', 'pending')->get();
    
    //         // Fetch tenants with verified status along with the referenced user details
    //         $verifiedTenants = Tenant::with('user')->where('tenant_status', 'approved')->get();
    
    //         // Return the lists of pending and verified tenants
    //         return response()->json([
    //             'pending_tenants' => $pendingTenants,
    //             'verified_tenants' => $verifiedTenants
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Handle any exceptions and return an error response
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function index(Request $request) {
    try {
        $userId = $request->input('user_id'); // Get the user ID from the request

        // Fetch tenants with pending status where the house owner's user ID matches the provided user ID
        // $pendingTenants = Tenant::with('user')
        $pendingTenants = Tenant::with(['user', 'house'])
            ->whereHas('house', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('tenant_status', 'pending')
            ->get();

        // Fetch tenants with verified status where the house owner's user ID matches the provided user ID
        $verifiedTenants = Tenant::with(['user', 'house'])
            ->whereHas('house', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('tenant_status', 'approved')
            ->get();

        // Return the lists of pending and verified tenants
        return response()->json([
            'pending_tenants' => $pendingTenants,
            'verified_tenants' => $verifiedTenants
        ], 200);
    } catch (\Exception $e) {
        // Handle any exceptions and return an error response
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function update(Request $request, $id)
    {
        $update_tenant = Tenant::findOrFail($id);

        $update_tenant ->fill($request->all())->save();

        return response()->json(['message' => 'Tenant status updated succesfully'], 200);
    }

    public function getAppliedHouses(Request $request, $userId)
    {
        try {
            $appliedHouses = Tenant::where('tenant_id', $userId)
                ->whereIn('tenant_status', ['Pending', 'Approved'])
                ->with(['house.owner']) // Load the house and its owner
                ->get();
    
            return response()->json($appliedHouses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // public function findByHouseId(Request $request, $houseId) 
    // {
    //     $update_tenant_contract = Tenant::where('house_id',$houseId);

    //     $update_tenant_contract ->fill($request->all())->save();

    //     return response()->json(['message' => 'Tenant contract signing status updated succesfully'], 200);
    // }

    public function findByHouseId(Request $request, $houseId) 
{
    // Find the tenant contract by house ID
    $update_tenant_contract = Tenant::where('house_id', $houseId)->first();
    $update_contract_status = House_Details::where('id', $houseId )->first();

    // Check if the tenant contract exists
    if (!$update_tenant_contract) {
        return response()->json(['message' => 'Tenant contract not found'], 404);
    }
    if (!$update_contract_status) {
        return response()->json(['message' => 'No contract was found'], 404);
    }

    // Fill the contract with the new data and save it
    $update_tenant_contract->fill($request->all());
    $update_tenant_contract->save();

    $update_contract_status->fill($request->all());
    $update_contract_status->save();

    return response()->json(['message' => 'Tenant contract signing status updated successfully'], 200);
}



public function getTenantByHouseId($houseId)
{
    try {
        // Fetch the tenant details based on house ID
        $tenant = Tenant::where('house_id', $houseId)->first();

        // Check if tenant exists
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found for this house ID'], 404);
        }

        // Return the tenant ID
        return response()->json(['tenant_id' => $tenant->tenant_id]);
    } catch (\Exception $e) {
        // Log the exception message
        Log::error('Error fetching tenant details: ' . $e->getMessage());

        // Handle any exceptions
        return response()->json(['error' => 'Server Error: ' . $e->getMessage()], 500);
    }
}
    

}
