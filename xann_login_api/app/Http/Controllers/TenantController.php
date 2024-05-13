<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
    public function index(Request $request)
{
    try {
        $userId = $request->input('user_id'); // Get the user ID from the request

        // Fetch tenants with pending status where the house owner's user ID matches the provided user ID
        $pendingTenants = Tenant::with('user')
            ->whereHas('house', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('tenant_status', 'pending')
            ->get();

        // Fetch tenants with verified status where the house owner's user ID matches the provided user ID
        $verifiedTenants = Tenant::with('user')
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
                ->whereIn('tenant_status', ['pending', 'approved'])
                ->get();
    
            return response()->json($appliedHouses, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    


    
    

}
