<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportCenter;
use App\Models\Equipment;
use App\Models\EquipmentRental;
use App\Models\User;
use App\Models\UserPoints;
use Illuminate\Support\Facades\Auth;

class EquipmentRentalController extends Controller
{
    /**
     * Step 1: Search for available equipment in a sport center
     */

     public function checkAvailability(Request $request)
{
    $request->validate([
        'sport_center_name' => 'required|exists:sport_centers,name',
        'equipment_name' => 'required|exists:equipment,name',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
    ]);

    // Find the sport center by name
    $sportCenter = SportCenter::where('name', $request->sport_center_name)
        ->with(['equipment' => function ($query) use ($request) {
            $query->where('name', $request->equipment_name);
        }])
        ->firstOrFail();

    $availableEquipment = $sportCenter->equipment->filter(function ($equipment) use ($request) {
        $rentedQuantity = EquipmentRental::where('equipmentID', $equipment->equipmentID)
            ->where('sport_center_id',$request->sport_center_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('startTime', [$request->startTime, $request->endTime])
                      ->orWhereBetween('endTime', [$request->startTime, $request->endTime]);
            })
            ->sum('quantity_rented');

        return $equipment->quantity_available > $rentedQuantity;
    });

    return response()->json(['available_equipment' => $availableEquipment], 200);
}
    // public function checkAvailability(Request $request)
    // {
    //     $request->validate([
    //         'sport_center_id' => 'required|exists:sport_centers,id',
    //         'date' => 'required|date',
    //         'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
    //         'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
    //     ]);

    //     $sportCenter = SportCenter::with('equipment')->findOrFail($request->sport_center_id);

    //     $availableEquipment = $sportCenter->equipment->filter(function ($equipment) use ($request) {
    //         $rentedQuantity = EquipmentRental::where('equipmentID', $equipment->id)
    //             ->where('sport_center_id', $request->sport_center_id)
    //             ->where('date', $request->date)
    //             ->where(function ($query) use ($request) {
    //                 $query->whereBetween('startTime', [$request->startTime, $request->endTime])
    //                       ->orWhereBetween('endTime', [$request->startTime, $request->endTime]);
    //             })
    //             ->sum('quantity_rented');

    //         return $equipment->quantity_available > $rentedQuantity;
    //     });

    //     return response()->json(['available_equipment' => $availableEquipment], 200);
    // }
 /**
     * Step 1.2: Fetch sports center by location
     */

    public function getSportsCentersByLocation(Request $request)
{
    $location = $request->query('location'); // Fetch location from query string

    $sportsCenters = SportCenter::where('location', 'LIKE', "%{$location}%")
                                ->get(['id', 'name', 'description', 'image']);

    return response()->json($sportsCenters);
}
    /**
     * Step 2: Fetch equipment details for rental
     */
    public function getEquipmentDetails($equipmentID)
    {
        $equipment = Equipment::with('sportCenters')->findOrFail($equipmentID);

        return response()->json([
            'name' => $equipment->name,
            'description' => $equipment->description,
            'price_per_hour' => $equipment->price_per_hour,
            'quantity_available' => $equipment->quantity_available,
            'condition' => $equipment->condition,
            'deposit_amount' => $equipment->deposit_amount,
            'images' => $equipment->images,
        ], 200);
    }

    /**
     * Step 3: Rent equipment
     */
    public function rentEquipment(Request $request)
    {
        $request->validate([
            'sport_center_id' => 'required|exists:sport_centers,id',
            'equipmentID' => 'required|exists:equipment,equipmentID',
            'date' => 'required|date',
            'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
            'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
            'quantity_rented' => 'required|integer|min:1',
        ]);

        $equipment = Equipment::findOrFail($request->equipmentID);

        // Check availability
        $rentedQuantity = EquipmentRental::where('equipmentID', $request->equipmentID)
            ->where('sport_center_id', $request->sport_center_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('startTime', [$request->startTime, $request->endTime])
                      ->orWhereBetween('endTime', [$request->startTime, $request->endTime]);
            })
            ->sum('quantity_rented');

        $remainingQuantity = $equipment->quantity_available - $rentedQuantity;

        if ($remainingQuantity < $request->quantity_rented) {
            return response()->json(['message' => 'Not enough equipment available'], 409);
        }

        // Create rental
        $rental = EquipmentRental::create([
            'userID' => Auth::id(),
            'sport_center_id' => $request->sport_center_id,
            'equipmentID' => $request->equipmentID,
            'date' => $request->date,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
            'quantity_rented' => $request->quantity_rented,
            'rentalStatus' => 'Ongoing',
        ]);
        $equipment->decrement('quantity_available', $request->quantity_rented);
        $this->awardPoints($request->user_id, 10); 

        return response()->json(['message' => 'Rental created successfully', 'rental' => $rental], 201);
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
    public function getMyRentals(Request $request)
{
    $userId = $request->user()->id;

    $rentals = EquipmentRental::where('userID', $userId)
        ->with([
            'sportCenter:id,name',
            'equipment:equipmentID,name'
        ])
        ->get(['rentalID', 'userID', 'sport_center_id', 'equipmentID', 'date', 'startTime', 'endTime', 'quantity_rented', 'rentalStatus']);

    $rentals->each(function ($rental) {
        $rental->sport_center_name = $rental->sportCenter->name;
        $rental->equipment_name = $rental->equipment->name;
        unset($rental->sportCenter, $rental->equipment);
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Rentals retrieved successfully',
        'rentals' => $rentals
    ]);
}

/**
 * Step 4: Receive return request
 */
public function receiveReturnRequest(Request $request)
{
    $validatedData = $request->validate([
        'rentalID' => 'required|exists:equipment_rentals,rentalID',
    ]);

    $rental = EquipmentRental::findOrFail($validatedData['rentalID']);

    if ($rental->rentalStatus !== 'Ongoing') {
        return response()->json(['message' => 'Invalid return request'], 400);
    }

    $rental->update(['rentalStatus' => 'Pending Return']);

    return response()->json(['message' => 'Return request received. Admin will validate.']);
}

/**
 * Step 5: Complete return （admin）
 */
public function completeReturn(Request $request)
{
    $validatedData = $request->validate([
        'rentalID' => 'required|exists:equipment_rentals,rentalID',
        'equipmentQuality' => 'required|string', // Assuming quality check is a string input
    ]);

    $rental = EquipmentRental::findOrFail($validatedData['rentalID']);

    if ($rental->rentalStatus !== 'Pending Return') {
        return response()->json(['message' => 'Invalid return process'], 400);
    }

    // Update equipment availability
    $equipment = Equipment::findOrFail($rental->equipmentID);
    $equipment->increment('quantity_available', $rental->quantity_rented);

    // Update rental details
    $rental->update([
        'rentalStatus' => 'Completed',
        'actualReturnDateTime' => now(),
        'equipmentQuality' => $validatedData['equipmentQuality'],
    ]);

    return redirect()->route('rentalReturns')->with('message', 'Return process completed successfully.');
}
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
//（admin）
public function showReturnRequests()
{
    $rentals = EquipmentRental::where('rentalStatus', 'Pending Return')
        ->with(['equipment', 'user'])
        ->get();

    return view('manageEquipment.manageRental', compact('rentals'));
}
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment
//（admin）manage equipment

// Display a listing of the equipment
public function index()
{
    $equipment = Equipment::all();
    return view('manageEquipment.viewEquipment', compact('equipment'));
}

// Show the form for creating new equipment
public function create()
{
    $sportCenters = SportCenter::all(); // Fetch all sport centers
    return view('manageEquipment.createEquipment', compact('sportCenters'));
}

// Store a newly created equipment in storage
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price_per_hour' => 'required|numeric',
        'quantity_available' => 'required|integer',
        'condition' => 'required|string|max:255',
        'deposit_amount' => 'required|numeric',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sport_center_id' => 'required|integer|exists:sport_centers,id',
    ]);

    $data = $request->all();

    if ($request->hasFile('image_path')) {
        $file = $request->file('image_path');
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $fileName);
        $data['image_path'] = $fileName;
    }

    Equipment::create($data);

    return redirect()->route('equipment.index')->with('success', 'Equipment created successfully.');
}

// Show the form for editing the specified equipment
public function edit($id)
{
    $sportCenters = SportCenter::all(); // Fetch all sport centers
    $equipment = Equipment::findOrFail($id);
    return view('manageEquipment.editEquipment', compact('equipment', 'sportCenters'));
}

// Update the specified equipment in storage
public function update(Request $request, $id)
{
    $equipment = Equipment::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price_per_hour' => 'required|numeric',
        'quantity_available' => 'required|integer',
        'condition' => 'required|string|max:255',
        'deposit_amount' => 'required|numeric',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sport_center_id' => 'required|integer|exists:sport_centers,id',
    ]);

    $data = $request->all();

    if ($request->hasFile('image_path')) {
        $file = $request->file('image_path');
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $fileName);
        $data['image_path'] = $fileName;

        // Delete the old image if it exists
        if ($equipment->image_path && file_exists(public_path('images/' . $equipment->image_path))) {
            unlink(public_path('images/' . $equipment->image_path));
        }
    }

    $equipment->update($data);

    return redirect()->route('equipment.index')->with('success', 'Equipment updated successfully.');
}

// Remove the specified equipment from storage
public function destroy($id)
{
    $equipment = Equipment::findOrFail($id);

    // Delete the image if it exists
    if ($equipment->image_path && file_exists(public_path('images/' . $equipment->image_path))) {
        unlink(public_path('images/' . $equipment->image_path));
    }

    $equipment->delete();

    return redirect()->route('equipment.index')->with('success', 'Equipment deleted successfully.');
}





}
