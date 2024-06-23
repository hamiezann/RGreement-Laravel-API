<?php

namespace App\Http\Controllers;

use App\Models\HouseIssue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Store a newly created resource in storage by landlord.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'landlord_id' => 'required|exists:users,id',
            'renter_id' => 'required|exists:users,id',
            'house_id' => 'required|exists:house_details,id',
            'description' => 'required|string',
            // 'image' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'amount_requested' => 'nullable|numeric',
            'status' => 'required|string|in:pending,accepted,rejected',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validated['image'] = $path;
        }
        $issue = HouseIssue::create($validated);

        return response()->json($issue, 201);
    }

    // public function viewByHouseId($house_id)
    // {
    //     $issues = HouseIssue::with(['images', 'landlord', 'renter', 'house'])
    //                         ->where('house_id', $house_id)
    //                         ->get();
    //                         $issues->each(function ($house) {
    //                             $house->images->each(function ($image) {
    //                                 $image->url = asset('storage/' .$image->path);
    //                             });
    //                         });
    //     return response()->json($issues);
    // }

    public function viewByHouseId($house_id)
{
    $issues = HouseIssue::with(['images', 'landlord', 'renter', 'house'])
                        ->where('house_id', $house_id)
                        ->get();

    // Iterate through each issue and process images
    $issues->each(function ($issue) {
        if ($issue->images) {
            $issue->images->each(function ($image) {
                // Assuming `path` is the attribute that stores the image path
                $image->url = asset('storage/' . $image->path);
            });
        }
    });

    return response()->json($issues);
}


    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:accepted,rejected',
        ]);

        $issue = HouseIssue::findOrFail($id);
        $issue->update($validated);


        return response()->json($issue);
    }

    public function approveDepositRelease(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|string|in:accepted',
    ]);

    // Update status in your database
    $issue = HouseIssue::findOrFail($id);
    $issue->update($validated);

    // Perform contract interaction to approve deposit release
    // Example: interact with Ethereum contract method approveDepositRelease
    // Ensure to pass necessary parameters like contractId, request index/id, etc.

    return response()->json($issue);
}

public function rejectDepositRelease(Request $request, $id)
{
    $validated = $request->validate([
        'status' => 'required|string|in:rejected',
    ]);

    // Update status in your database
    $issue = HouseIssue::findOrFail($id);
    $issue->update($validated);

    // Perform contract interaction to reject deposit release
    // Example: interact with Ethereum contract method rejectDepositRelease
    // Ensure to pass necessary parameters like contractId, request index/id, etc.

    return response()->json($issue);
}

}
