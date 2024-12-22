<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gift;
use Illuminate\Support\Facades\Log;

class GiftController extends Controller
{
    // Display all gifts
    public function index()
    {
        $gifts = Gift::all();
        return view('reward&gift.viewGift', compact('gifts'));
    }

    // Show the form to create a new gift
    public function create()
    {
        return view('reward&gift.createGift');
    }

    // Store the new gift
    public function store(Request $request)
    {
        Log::info('Incoming request:', $request->all());
 
        $request->validate([
            'name' => 'required|string|max:255',
            'points_needed' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
 
        $imagePath = null;
 
        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            if ($file->isValid()) {
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $fileName);
                $imagePath = $fileName;
            }
        }
 
        $gift = Gift::create([
            'name' => $request->name,
            'points_needed' => $request->points_needed,
            'description' => $request->description,
            'image_path' => $imagePath,
        ]);
 
        if ($gift) {
            Log::info('Gift created successfully:', $gift->toArray());
        } else {
            Log::error('Failed to create gift.');
        }
 
        return redirect()->route('admin.gifts.index')->with('success', 'Gift created successfully.');
    }


    // Show the edit form for a gift
    public function edit($id)
    {
        $gift = Gift::findOrFail($id);
        return view('reward&gift.editGift', compact('gift'));
    }

    // Update an existing gift
    public function update(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'points_needed' => 'required|integer|min:1',
        'description' => 'nullable|string',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Find the gift by ID, or fail if not found
    $gift = Gift::findOrFail($id);

    // Handle image upload if provided
    if ($request->hasFile('image_path')) {
        $file = $request->file('image_path');
        if ($file->isValid()) {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $imagePath = $fileName;

            // Delete the old image if it exists
            if ($gift->image_path && file_exists(public_path('images/' . $gift->image_path))) {
                unlink(public_path('images/' . $gift->image_path));
            }

            $gift->image_path = $imagePath; // Update the image path
        }
    }

    // Update the gift details
    $gift->update([
        'name' => $request->name,
        'points_needed' => $request->points_needed,
        'description' => $request->description,
        'image_path' => $gift->image_path, // Use the updated image path
    ]);

    // Redirect to the gift list with a success message
    return redirect()->route('admin.gifts.index')->with('success', 'Gift updated successfully.');
}


    // Delete a gift
    public function destroy($id)
    {
        $gift = Gift::findOrFail($id);
        $gift->delete();

        return redirect()->route('admin.gifts.index')->with('success', 'Gift deleted successfully.');
    }
}

