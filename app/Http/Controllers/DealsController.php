<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\User;
use App\Models\UserPoints;
use Illuminate\Support\Facades\Log;

class DealsController extends Controller
{
    // Create a new deal

    public function createDeal(Request $request)
    {
        Log::info('Incoming request:', $request->all());
    
        $validated = $request->validate([
            'userID' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 

        ]);

        
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            if ($file->isValid()) {
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->move(public_path('images'), $fileName);
                $validated['image_path'] = $fileName; 
            }
        } else {
            $validated['image_path'] = null; 
        }
        $deal = Deal::create($validated);
        $this->awardPoints($validated['userID'], 10); 

        return response()->json(['message' => 'Deal created successfully', 'deal' => $deal], 201);
    }
// Helper function to award points
// Helper function to award points
// Helper function to award points
private function awardPoints($userId, $points)
{
    $userPoints = UserPoints::firstOrCreate(['user_id' => $userId]);
    $userPoints->points += $points;
    $userPoints->save();
}
// Helper function to award points
// Helper function to award points
// Helper function to award points

    // Edit an existing deal
    public function editDeal(Request $request, $dealID)
    {
        $deal = Deal::findOrFail($dealID);
    if ($deal->userID != $request->user()->id) {
       return response()->json(['message' => 'Unauthorized'], 403);
   }
    $validated = $request->validate([
       'title' => 'string|max:255',
       'description' => 'string',
       'price' => 'numeric|min:0',
       'location' => 'string',
       'image_path' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
   ]);
    if ($request->hasFile('image_path')) {
       $file = $request->file('image_path');
       if ($file->isValid()) {
           $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
           $filePath = $file->move(public_path('images'), $fileName);
            $validated['image_path'] = $fileName; 
       }
   } elseif (filter_var($request->input('image_path'), FILTER_VALIDATE_URL)) {
       // Handle URL
       $validated['image_path'] = $request->input('image_path');
   } else {
       unset($validated['image_path']);
   }
    $deal->update($validated);
    return response()->json(['message' => 'Deal updated successfully', 'deal' => $deal]);
    }

    // Delete a deal
    public function deleteDeal($dealID)
    {
        $deal = Deal::findOrFail($dealID);

        if ($deal->userID != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $deal->delete();

        return response()->json(['message' => 'Deal deleted successfully']);
    }

   // View deals listed by other users
    public function viewAllDeals()
    {
        
        $userID = $request->user()->id; 
        $deals = Deal::where('userID', $userID)
                    ->with('user:id,name')
                    ->get()
                    ->map(function ($deal) {
                        $deal->image_url = $deal->image_path ? asset($deal->image_path) : null; // Generate the full URL
                        return $deal;
                    });
        return response()->json($deals);
    }
    // public function viewAllDeals()
    // {
    //     $deals = Deal::join('users', 'deals.userID', '=', 'users.id')
    //         ->where('deals.status', 'Approved')
    //         ->select('deals.*', 'users.name as name')
    //         ->get();
    
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Deals retrieved successfully',
    //         'deals' => $deals
    //     ]);
    // }
    // View deals listed by the authenticated user
    public function viewMyDeals(Request $request)
{
    $userID = $request->user()->id; // Get the authenticated user's ID

    // $deals = Deal::where('userID', $userID) ->with('user:id,name')->get();

    // return response()->json($deals);
    $deals = Deal::where('userID', $userID)
    ->with('user:id,name')
    ->get()
    ->map(function ($deal) {
        $deal->image_url = $deal->image_path ? asset($deal->image_path) : null; // Generate the full URL
        return $deal;
    });
return response()->json($deals);
}

////////////////////ADMIN////////////////////
////////////////////ADMIN////////////////////
////////////////////ADMIN////////////////////
////////////////////ADMIN////////////////////
////////////////////ADMIN////////////////////

    // Approve a deal
    public function approveDeal($dealID)
    {
        $deal = Deal::findOrFail($dealID);

        if ($deal->status !== 'Pending') {
            return response()->json(['message' => 'Deal is already processed'], 400);
        }

        $deal->update(['status' => 'Approved']);

        // return response()->json(['message' => 'Deal approved successfully']);
    return redirect()->route('showDeals')->with('message', 'Deal approved successfully.');

    }

    // Reject a deal
    public function rejectDeal($dealID)
    {
        $deal = Deal::findOrFail($dealID);

        if ($deal->status !== 'Pending') {
            return response()->json(['message' => 'Deal is already processed'], 400);
        }

        $deal->update(['status' => 'Rejected']);

        // return response()->json(['message' => 'Deal rejected successfully']);
        return redirect()->route('showDeals')->with('message', 'Deal rejected successfully.');
    }
 //show all deals
    public function showDeals()
{
    $deals = Deal::with('user')->orderBy('created_at', 'desc')->get();
    return view('manageDeal', compact('deals'));
}
}
