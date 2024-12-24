<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\SportCenter;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    // Display a listing of courts for a specific sport center
    public function index($id)
{
    // Retrieve the SportCenter instance using the provided ID
    $sportCenter = SportCenter::findOrFail($id);

    // Get all courts associated with the retrieved SportCenter
    $courts = $sportCenter->courts;

    // Return the view with the courts and sportCenter data
    return view('manageCourt.viewCourt', compact('courts', 'sportCenter'));
}

// Show the form for creating a new court
public function create($id)
{
    // Retrieve the SportCenter instance using the provided ID
    $sportCenter = SportCenter::findOrFail($id);

    // Return the view with the sportCenter data
    return view('manageCourt.createCourt', compact('sportCenter'));
}

public function store(Request $request, $sportCenterId)
{
    // Retrieve the SportCenter instance using the provided ID
    $sportCenter = SportCenter::findOrFail($sportCenterId);

    // Validate the incoming request data
    $request->validate([
        'number' => 'required|integer',
        'type' => 'required|string|max:255',
        'availability' => 'required|boolean',
    ]);

    // Create a new court associated with the retrieved SportCenter
    $sportCenter->courts()->create($request->all());

    // Redirect to the courts index route with a success message
    return redirect()->route('sportcenters.courts.index', $sportCenter->id)->with('success', 'Court created successfully.');
}

// Show the form for editing a court
public function edit($sportCenterId, $courtId)
{
    // Retrieve the SportCenter and Court instances using the provided IDs
    $sportCenter = SportCenter::findOrFail($sportCenterId);
    $court = Court::findOrFail($courtId);

    // Return the view with the sportCenter and court data
    return view('manageCourt.editCourt', compact('sportCenter', 'court'));
}

// Update a court
public function update(Request $request, $courtId)
{
    // Retrieve the Court instance using the provided ID
    $court = Court::findOrFail($courtId);

    // Validate the incoming request data
    $request->validate([
        'number' => 'required|integer',
        'type' => 'required|string|max:255',
        'availability' => 'required|boolean',
    ]);

    // Update the court with the validated data
    $court->update($request->all());

    // Redirect to the courts index route with a success message
    return redirect()->route('sportcenters.courts.index', $court->sport_center_id)->with('success', 'Court updated successfully.');
}

// Delete a court
public function destroy($courtId)
{
    // Retrieve the Court instance using the provided ID
    $court = Court::findOrFail($courtId);

    // Delete the court
    $court->delete();

    // Redirect to the courts index route with a success message
    return redirect()->route('sportcenters.courts.index', $court->sport_center_id)->with('success', 'Court deleted successfully.');
}
}
