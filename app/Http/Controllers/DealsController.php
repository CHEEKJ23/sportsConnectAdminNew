<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class DealsController extends Controller
{
    // Create a new deal
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire



    public function createDeal(Request $request)
    {
        Log::info('Incoming request:', $request->all());
    
        $validated = $request->validate([
            'userID' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string',
            'image_path' => 'nullable|string|max:2048', 
        ]);
    
        if ($request->hasFile('image_path')) {
            
            $validated['image_path'] = $request->file('image_path')->store('deals', 'public');
        } elseif (filter_var($request->input('image_path'), FILTER_VALIDATE_URL)) {
          
            $validated['image_path'] = $request->input('image_path');
        } else {
            $validated['image_path'] = null; 
        }
    
        $deal = Deal::create($validated);
    
        return response()->json(['message' => 'Deal created successfully', 'deal' => $deal], 201);
    }



 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
 //this method use link to store image but will expire
//  public function createDeal(Request $request)
//  {
//      Log::info('Incoming request:', $request->all());
 
//      $validated = $request->validate([
//          'userID' => 'required|exists:users,id',
//          'title' => 'required|string|max:255',
//          'description' => 'required|string',
//          'price' => 'required|numeric|min:0',
//          'location' => 'required|string',
//          'images' => 'nullable|array', // Expect an array of images
//          'images.*' => 'nullable|string|max:2048', // Each image can be a URL, base64, or file
//      ]);
 
//      $imagePaths = [];
 
//      if ($request->has('images')) {
//          foreach ($request->file('images', []) as $image) {
//              if ($image->isValid()) {
//                  // Handle file upload
//                  $imageName = $image->getClientOriginalName();
//                  $image->move(public_path('images'), $imageName);
//                  $imagePaths[] = 'images/' . $imageName;
//              }
//          }
 
//          foreach ($request->input('images', []) as $image) {
//              if (filter_var($image, FILTER_VALIDATE_URL)) {
//                  // Handle URL
//                  $imagePaths[] = $image;
//              } elseif (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
//                  // Handle base64 encoded image
//                  $imageData = substr($image, strpos($image, ',') + 1);
//                  $type = strtolower($type[1]); // jpg, png, gif, etc.
 
//                  if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
//                      return response()->json(['message' => 'Invalid image type'], 400);
//                  }
 
//                  $imageData = base64_decode($imageData);
 
//                  if ($imageData === false) {
//                      return response()->json(['message' => 'Base64 decode failed'], 400);
//                  }
 
//                  $fileName = uniqid() . '.' . $type;
//                  $filePath = 'images/' . $fileName; // Store in public/images
//                  Storage::disk('public')->put($filePath, $imageData);
 
//                  $imagePaths[] = $filePath;
//              }
//          }
//      }
 
//      $validated['image_path'] = json_encode($imagePaths); // Store as JSON
 
//      $deal = Deal::create($validated);
 
//      return response()->json(['message' => 'Deal created successfully', 'deal' => $deal], 201);
//  }






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
            // 'image_path' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'image_path' => 'nullable|string|max:2048', // Allow string for URL

        ]);

        // if ($request->hasFile('image_path')) {
        //     $validated['image_path'] = $request->file('image_path')->store('deals', 'public');
        // }

        if ($request->hasFile('image_path')) {
            // Handle file upload
            $validated['image_path'] = $request->file('image_path')->store('deals', 'public');
        } elseif (filter_var($request->input('image_path'), FILTER_VALIDATE_URL)) {
            // Handle URL
            $validated['image_path'] = $request->input('image_path');
        } else {
            $validated['image_path'] = null; // Set to null if no valid image path is provided
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
        $deals = Deal::where('status', 'Approved')
        ->with('user:id,name')
        ->get();

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

    $deals = Deal::where('userID', $userID) ->with('user:id,name')->get();

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
