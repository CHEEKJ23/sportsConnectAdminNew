<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SportCenter;
use App\Models\Equipment;
use App\Models\EquipmentRental;
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
            'rentalStatus' => 'Pending',
        ]);
        $equipment->decrement('quantity_available', $request->quantity_rented);
        return response()->json(['message' => 'Rental created successfully', 'rental' => $rental], 201);
    }
}
