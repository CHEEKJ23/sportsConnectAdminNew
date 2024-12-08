<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Court;
use App\Models\SportCenter;
use App\Models\User;
use App\Models\Booking; 
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
     // Step 1: Search for available sports centers
     public function searchSportCenters(Request $request)
     {
        
         $validated = $request->validate([
             'sportType' => 'required|string',
             'location' => 'required|string',
             'date' => 'required|date',
            //  'startTime' => 'required|date_format:H:i',
            //  'endTime' => 'required|date_format:H:i|after:startTime',
            'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
         ]);
 
         // Find sports centers with courts matching the given type and location
         $sportCenters = SportCenter::where('location', $validated['location'])
            ->whereHas('courts', function ($query) use ($validated) {
                $query->where('type', $validated['sportType'])
                      ->whereDoesntHave('bookings', function ($query) use ($validated) {
                          $query->where('date', $validated['date'])
                                ->where(function ($q) use ($validated) {
                                    $q->whereBetween('startTime', [$validated['startTime'], $validated['endTime']])
                                      ->orWhereBetween('endTime', [$validated['startTime'], $validated['endTime']]);
                                });
                      });
            })->get();
 
         return response()->json($sportCenters);
     }
 
     // Step 2: Get available courts for a selected sports center
     public function getAvailableCourts(Request $request, $sportCenterId)
{
    $validated = $request->validate([
        // 'sport_center_id'=>'required|int',
        'sportType' => 'required|string',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
    ]);

    $availableCourts = Court::where('sport_center_id', $sportCenterId)
    // ->where('sport_center_id', $validated['sport_center_id'])

        ->where('type', $validated['sportType'])
        ->get()
        ->filter(function ($court) use ($validated) {
            return $court->isAvailable($validated['date'], $validated['startTime'], $validated['endTime']);
        });

    return response()->json($availableCourts);
}
     
    //  public function bookCourt(Request $request)
    //  {
    //      $validated = $request->validate([
    //          'user_id' => 'required|exists:users,id',
    //          'sport_center_id' => 'required|exists:sport_centers,id',
    //          'court_id' => 'required|exists:courts,id',
    //          'date' => 'required|date',
    //          'startTime' => 'required|date_format:H:i',
    //          'endTime' => 'required|date_format:H:i|after:startTime',
    //      ]);
 
        
    //      $booking = Booking::create($validated);
 
        
    //      Court::where('id', $validated['court_id'])->update(['availability' => false]);
 
    //      return response()->json(['message' => 'Booking successful', 'booking' => $booking]);
    //  }

    // Step 3: Book a court
    public function bookCourt(Request $request)
{
    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'sport_center_id' => 'required|exists:sport_centers,id',
        'court_id' => 'required|exists:courts,id',
        // 'sportType' => 'required|string',
        // 'location' => 'required|string',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
    ]);

    // Check if the court is already booked for the selected date and time range
    $isBooked = Booking::where('court_id', $validatedData['court_id'])
        ->where('date', $validatedData['date'])
        ->where('status', 'confirmed')
        ->where(function ($query) use ($validatedData) {
            $query->whereBetween('startTime', [$validatedData['startTime'], $validatedData['endTime']])
                ->orWhereBetween('endTime', [$validatedData['startTime'], $validatedData['endTime']])
                ->orWhere(function ($q) use ($validatedData) {
                    $q->where('startTime', '<=', $validatedData['startTime'])
                      ->where('endTime', '>=', $validatedData['endTime']);
                });
        })
        ->exists();

    if ($isBooked) {
        return response()->json(['message' => 'Court is already booked for the selected time'], 409);
    }

    // Create the booking
    $validatedData['status'] = 'confirmed';
    $booking = Booking::create($validatedData);

    return response()->json(['message' => 'Court booked successfully', 'booking' => $booking], 201);
}

public function getMyBookings(Request $request) {
    $userId = $request->user()->id;

    $bookings = Booking::where('user_id', $userId)
        ->with([
            'sportCenter:id,name',
            'court:id,type'   
        ])
        ->get(['id', 'user_id', 'sport_center_id', 'court_id', 'date', 'startTime', 'endTime']); 

    $bookings->each(function ($booking) {
        $booking->sport_center_name = $booking->sportCenter->name;
        $booking->sport_type_name = $booking->court->type;
        unset($booking->sportCenter, $booking->court); 
    });

    return response()->json([
        'status' => 'success',
        'message' => 'Bookings retrieved successfully',
        'bookings' => $bookings
    ]);
}

public function updateBooking(Request $request, $bookingId)
{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
    ]);

    $booking = Booking::where('id', $bookingId)
        ->where('user_id', $request->user()->id)
        ->firstOrFail();

    // Check if the court is available for the new date and time
    $isAvailable = $booking->court->isAvailable($validatedData['date'], $validatedData['startTime'], $validatedData['endTime']);

    if (!$isAvailable) {
        return response()->json(['message' => 'The court is not available for the selected time'], 409);
    }

    // Update the booking
    $booking->update($validatedData);

    return response()->json(['message' => 'Booking updated successfully', 'booking' => $booking]);
}

}
