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
use App\Models\UserPoints;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
     // Step 1: Search for available sports centers
     public function searchSportCenters(Request $request)
{
    $validated = $request->validate([
        'sportType' => 'required|string',
        'location' => 'required|string',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
    ]);

    // Ensure startTime and endTime are in the correct format
    $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validated['startTime']));
    $endTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validated['endTime']));

    // Adjust endTime if it is earlier than startTime, assuming it belongs to the next day
    if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
    }

    // Your existing logic to find sport centers
    $sportCenters = SportCenter::where('location', $validated['location'])
        ->whereHas('courts', function ($query) use ($validated, $startTime, $endTime) {
            $query->where('type', $validated['sportType'])
                  ->whereDoesntHave('bookings', function ($query) use ($validated, $startTime, $endTime) {
                      $query->where('date', $validated['date'])
                            ->where(function ($q) use ($startTime, $endTime) {
                                $q->whereBetween('startTime', [$startTime->format('H:i'), $endTime->format('H:i')])
                                  ->orWhereBetween('endTime', [$startTime->format('H:i'), $endTime->format('H:i')]);
                            });
                  });
        })->get();

    return response()->json($sportCenters);
}

// Helper function to format time
private function formatTime($time)
{
    $parts = explode(':', $time);
    $hour = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
    $minute = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
    return "$hour:$minute";
}
 
     // Step 2: Get available courts for a selected sports center
     public function getAvailableCourts(Request $request, $sportCenterId)
{
    $validated = $request->validate([
        // 'sport_center_id'=>'required|int',
        'sportType' => 'required|string',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
    ]);
    $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validated['startTime']));
    $endTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validated['endTime']));

    // Adjust endTime if it is earlier than startTime, assuming it belongs to the next day
    if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
    }
    $availableCourts = Court::where('sport_center_id', $sportCenterId)
    // ->where('sport_center_id', $validated['sport_center_id'])

        ->where('type', $validated['sportType'])
        ->get()
        ->filter(function ($court) use ($validated) {
            return $court->isAvailable($validated['date'], $validated['startTime'], $validated['endTime']);
        });

    return response()->json($availableCourts);
}
     


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
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
    ]);
    $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validatedData['startTime']));
    $endTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validatedData['endTime']));

    // Adjust endTime if it is earlier than startTime, assuming it belongs to the next day
    if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
    }
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

    $this->awardPoints($validatedData['user_id'], 10); 

    return response()->json(['message' => 'Court booked successfully', 'booking' => $booking], 201);
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

public function getMyBookings(Request $request) {
    $userId = $request->user()->id;

    $bookings = Booking::where('user_id', $userId)
        ->with([
            'sportCenter:id,name',
            'court:id,type,number'   
        ])
        ->get(['id', 'user_id', 'sport_center_id', 'court_id', 'date', 'startTime', 'endTime']); 

    $bookings->each(function ($booking) {
        $booking->sport_center_name = $booking->sportCenter->name;
        $booking->sport_type_name = $booking->court->type;
        $booking->court_number = $booking->court->number;
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
        'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
    ]);
    $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validatedData['startTime']));
    $endTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validatedData['endTime']));

    // Adjust endTime if it is earlier than startTime, assuming it belongs to the next day
    if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
    }
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

    // Step 4: Cancel a booking
    public function cancelBooking(Request $request, $bookingId)
{
    $userId = $request->user()->id;

    // Find the booking
    $booking = Booking::where('id', $bookingId)
        ->where('user_id', $userId)
        ->firstOrFail();

    // Calculate the time difference
    $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date . ' ' . $booking->startTime);
    $now = Carbon::now();
    $hoursUntilStart = $now->diffInHours($startTime, false);

    // Debugging: Output the calculated hours until start
    \Log::info("Hours until start: $hoursUntilStart");

    // Disallow cancellation if the booking is for the next day
    if ($hoursUntilStart < 24) {
        return response()->json([
            'status' => 'error',
            'message' => 'Cancellations are not allowed within 24 hours of the booking start time.',
        ], 403);
    }

    // Determine refund percentage
    $refundPercentage = 0;
    if ($hoursUntilStart >= 48) {
        $refundPercentage = 100;
    } elseif ($hoursUntilStart >= 24) {
        $refundPercentage = 50;
    }

    // Delete the booking
    $booking->delete();

    // Return response with refund information
    return response()->json([
        'status' => 'success',
        'message' => "Booking deleted successfully. You will receive a $refundPercentage% refund.",
    ]);
}

//admin
//admin
//admin
//admin
//admin
//admin
//admin
//admin
//admin
public function showBookingCalendar()
{
    // Fetch all bookings with related sport center and court information
    $bookings = Booking::with(['sportCenter:id,name', 'court:id,type,number'])
        ->get(['id', 'sport_center_id', 'court_id', 'date', 'startTime', 'endTime']);

    // Format bookings for FullCalendar
    $events = $bookings->map(function ($booking) {
        return [
            'title' => $booking->sportCenter->name . ' - ' . $booking->court->type . ' Court ' . $booking->court->number,
            'start' => $booking->date . 'T' . $booking->startTime,
            'end' => $booking->date . 'T' . $booking->endTime,
        ];
    });

    // Fetch all sport centers
    $sportCenters = SportCenter::all();

    // Pass both events and sport centers to the view
    return view('manageCourtBooking', compact('events', 'sportCenters'));
}

public function adminCreateBooking(Request $request)
{
    $validatedData = $request->validate([
        'user_id' =>  'nullable|exists:users,id',
        'sport_center_id' => 'required|exists:sport_centers,id',
        'court_id' => 'required|exists:courts,id',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{2}:\d{2}$/', // Adjusted regex for HH:MM format
        'endTime' => 'required|regex:/^\d{2}:\d{2}$/',   // Adjusted regex for HH:MM format
    ]);

    $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validatedData['startTime']));
    $endTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validatedData['endTime']));

    if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
    }

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

public function getCourtTypes($sportCenterId)
{
    $courtTypes = Court::where('sport_center_id', $sportCenterId)
        ->distinct()
        ->pluck('type');

    return response()->json($courtTypes);
}

public function getCourts($sportCenterId, $courtType)
{
    $courts = Court::where('sport_center_id', $sportCenterId)
        ->where('type', $courtType)
        ->get(['id', 'number']);

    return response()->json($courts);
}
//admin
//admin
//admin
//admin
//admin
//admin
//admin
//admin
//admin
public function searchCourtAvailability(Request $request)
{

    $validated = $request->validate([
        'sport_center_name' => 'nullable|string',
        'court_type' => 'nullable|string',
        'date' => 'required|date',
        'startTime' => 'required|regex:/^\d{2}:\d{2}$/',
        'endTime' => 'required|regex:/^\d{2}:\d{2}$/',
    ]);

    $startTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validated['startTime']));
    $endTime = \Carbon\Carbon::createFromFormat('H:i', $this->formatTime($validated['endTime']));

    if ($endTime->lessThanOrEqualTo($startTime)) {
        $endTime->addDay();
    }

    $query = Court::query();

    if (!empty($validated['sport_center_name'])) {
        $query->whereHas('sportCenter', function ($q) use ($validated) {
            $q->where('name', 'like', '%' . $validated['sport_center_name'] . '%');
        });
    }

    if (!empty($validated['court_type'])) {
        $query->where('type', $validated['court_type']);
    }

    $courts = $query->with(['sportCenter:id,name', 'bookings' => function ($q) use ($validated, $startTime, $endTime) {
        $q->where('date', $validated['date'])
          ->where(function ($q) use ($startTime, $endTime) {
              $q->whereBetween('startTime', [$startTime->format('H:i'), $endTime->format('H:i')])
                ->orWhereBetween('endTime', [$startTime->format('H:i'), $endTime->format('H:i')]);
          });
    }])->get();

    return view('adminOverview', compact('courts', 'validated'));
}

public function showCourtAvailabilityForm()
{
    $courts = collect(); // Empty collection
    $validated = []; // Empty array for validated data

    return view('adminOverview', compact('courts', 'validated'));
}

}

