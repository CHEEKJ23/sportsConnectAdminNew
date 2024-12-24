<?php

namespace App\Http\Controllers;

use App\Models\SportCenter;
use App\Models\Court;
use Illuminate\Http\Request;

class SportCenterController extends Controller
{
    // Display a listing of sport centers
    public function index()
    {
        $sportCenters = SportCenter::with('courts')->get();
        return view('manageSportsCenter.viewSportCenter', compact('sportCenters'));
    }

    // Show the form for creating a new sport center
    public function create()
    {
        return view('manageSportsCenter.createSportCenter');
    }

    // Store a newly created sport center
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
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

        SportCenter::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'image' => $imagePath,
            'price' => $request->price,
        ]);

        return redirect()->route('sportcenters.index')->with('success', 'Sport Center created successfully.');
    }

    // Show the form for editing a sport center
    public function edit($id)
    {
        $sportCenter = SportCenter::findOrFail($id);
        return view('manageSportsCenter.editSportCenter', compact('sportCenter'));
    }

    // Update a sport center
    public function update(Request $request, SportCenter $sportCenter)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
        ]);

        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('images', 'public');               //problem here
            $sportCenter->update(['image' => $imagePath]);
        }

        $sportCenter->update($request->only(['name', 'description', 'location', 'price']));

        return redirect()->route('sportcenters.index')->with('success', 'Sport Center updated successfully.');
    }

    // Delete a sport center
    public function destroy($id)
    {
        $sportCenter = SportCenter::findOrFail($id);
        $sportCenter->delete();
        return redirect()->route('sportcenters.index')->with('success', 'Sport Center deleted successfully.');
    }

   
}

