<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Court;
use App\Models\Booking;
use App\Models\User;
use App\Models\UserPoints;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function createActivity(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'sportType' => 'required|string',
            'sport_center_id' => 'required|exists:sport_centers,id',
            'date' => 'required|date|after_or_equal:today',
            'startTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/',
            'endTime' => 'required|regex:/^\d{1,2}:\d{1,2}$/|after:startTime',
            'player_quantity' => 'required|integer|min:1',
            'price_per_pax' => 'required|numeric|min:0',
        ]);

        $activity = Activity::create($validated);
        $this->awardPoints($validatedData['user_id'], 10); 

        return response()->json(['message' => 'Activity created successfully', 'activity' => $activity], 201);
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

    
//join activity
//join activity
//join activity
    public function joinActivity(Request $request, $activityId)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'player_quantity' => 'required|integer|min:1',
    ]);

    // Fetch the activity
    $activity = Activity::find($activityId);

    if (!$activity) {
        return response()->json(['message' => 'Activity not found'], 404);
    }

// Check if the activity exists and is not canceled
if ($activity->status === 'cancelled') {
    return response()->json(['message' => 'This activity has been cancelled'], 400);
}

// Calculate the current number of players
$currentPlayers = $activity->players()->sum('player_quantity'); // Sum the 'player_quantity' for all joined players

// Calculate remaining slots
$remainingSlots = $activity->player_quantity - $currentPlayers;

// Check if the requested player quantity exceeds the remaining slots
if ($validated['player_quantity'] > $remainingSlots) {
    return response()->json([
        'message' => 'Not enough slots available',
        'remaining_slots' => $remainingSlots
    ], 409);
}


    // Add the user to the activity
    $activity->players()->attach($validated['user_id'], [
        'player_quantity' => $validated['player_quantity'],
    ]);

    // Automatically book the court if the activity is now full
$currentPlayers = $activity->players()->sum('player_quantity'); // Calculate the total number of joined players

if ($currentPlayers >= $activity->player_quantity) {
    // Update activity status to confirmed
    $activity->status = 'confirmed';
    $activity->save();
    $this->awardPoints($validated['user_id'], 10); 

    // Find an available court
    $court = Court::where('sport_center_id', $activity->sport_center_id)
        ->where('type', $activity->sportType)
        ->get()
        ->filter(function ($court) use ($activity) {
            return $court->isAvailable($activity->date, $activity->startTime, $activity->endTime);
        })->first();

    if ($court) {
        // Book the court
        Booking::create([
            'user_id' => $activity->user_id,
            'sport_center_id' => $activity->sport_center_id,
            'court_id' => $court->id,
            'date' => $activity->date,
            'startTime' => $activity->startTime,
            'endTime' => $activity->endTime,
            'status' => 'confirmed',
        ]);
    } else {
        // If no courts are available, cancel the activity
        $activity->status = 'cancelled';
        $activity->save();

        return response()->json(['message' => 'Activity cancelled: no courts available'], 409);
    }
}


    return response()->json(['message' => 'Joined activity successfully', 'activity' => $activity], 200);
}
//get specific activity
// public function getSpecificActivity(Request $request)
// {
//     $activities = Activity::with(['creator', 'sportCenter'])
//         ->when($request->input('sportType'), function ($query, $sportType) {
//             $query->where('sportType', $sportType);
//         })
//         ->when($request->input('location'), function ($query, $location) {
//             $query->whereHas('sportCenter', function ($subQuery) use ($location) {
//                 $subQuery->where('location', $location);
//             });
//         })
//         ->get();

//     return response()->json($activities, 200);
// }
public function getSpecificActivity(Request $request)
{
    $userId = $request->user()->id; 
    $activities = Activity::with(['creator', 'sportCenter'])
        ->when($request->input('sportType'), function ($query, $sportType) {
            $query->where('sportType', $sportType);
        })
        ->when($request->input('location'), function ($query, $location) {
            $query->whereHas('sportCenter', function ($subQuery) use ($location) {
                $subQuery->where('location', $location);
            });
        })
        ->where('activities.user_id', '!=', $userId) 
        ->select('activities.*', 'sport_centers.name as sport_center_name', 'sport_centers.location as sport_center_location', 'users.name as creator_name')
        ->join('sport_centers', 'activities.sport_center_id', '=', 'sport_centers.id')
        ->join('users', 'activities.user_id', '=', 'users.id')
        ->get();

    return response()->json($activities, 200);
}


   // Show all activities except those created by the current user
   public function getAllActivitiesExceptUser(Request $request)
   {
       $userId = $request->user()->id; // Get the authenticated user's ID
   
       $activities = Activity::where('activities.user_id', '!=', $userId)
                             ->join('sport_centers', 'activities.sport_center_id', '=', 'sport_centers.id')
                             ->join('users', 'activities.user_id', '=', 'users.id')
                             ->select('activities.*', 'sport_centers.name as sport_center_name', 'sport_centers.location as sport_center_location', 'users.name as creator_name')
                             ->get();
   
       return response()->json($activities, 200);
   }

   // Show all activities created by the current user
   public function getUserActivities(Request $request)
{
    $userId = $request->user()->id; // Get the authenticated user's ID

    $activities = Activity::where('activities.user_id', $userId)
                          ->join('sport_centers', 'activities.sport_center_id', '=', 'sport_centers.id')
                          ->select('activities.*', 'sport_centers.name as sport_center_name', 'sport_centers.location as sport_center_location')
                          ->get();
                          $activities->each(function ($activity) {
                            $activity->players = User::join('activity_user', 'users.id', '=', 'activity_user.user_id')
                                                     ->where('activity_user.activity_id', $activity->id)
                                                     ->select('users.id', 'users.name')
                                                     ->get();
                        });
    return response()->json($activities, 200);
}


// Fetch sport centers by location
//use equipment rental controller 
//use equipment rental controller 
//use equipment rental controller 

// Fetch available sport types at a sport center
public function getAvailableSportTypes(Request $request, $sportCenterId)
{
    $sportTypes = Court::where('sport_center_id', $sportCenterId)
                       ->distinct()
                       ->pluck('type');

    return response()->json($sportTypes);
}

// Show activities the user has joined and other users who joined
// Show activities the user has joined and other users who joined
public function getJoinedActivities(Request $request)
   {
    //    $userId = $request->user()->id;
    //     $activities = Activity::whereHas('players', function ($query) use ($userId) {
    //                            $query->where('user_id', $userId);
    //                        })
    //                        ->with(['players' => function ($query) {
    //                            $query->select('users.id', 'users.name');
    //                        }])
    //                        ->get();
    //     return response()->json($activities, 200);
    $userId = $request->user()->id; 
    $activities = Activity::join('activity_user', 'activities.id', '=', 'activity_user.activity_id')
                         ->join('sport_centers', 'activities.sport_center_id', '=', 'sport_centers.id')
                         ->where('activity_user.user_id', $userId)
                         ->select(
                             'activities.*',
                             'sport_centers.name as sport_center_name',
                             'sport_centers.location as sport_center_location'
                         )
                         ->distinct()
                         ->get();
    // Fetch players for each activity
   $activities->each(function ($activity) {
       $activity->players = User::join('activity_user', 'users.id', '=', 'activity_user.user_id')
                                ->where('activity_user.activity_id', $activity->id)
                                ->select('users.id', 'users.name')
                                ->get();
   });
    return response()->json($activities, 200);
   }

//cancel activity that the user has created
public function cancelActivity(Request $request, $activityId) {

   $userId = $request->user()->id; // Get the authenticated user's ID
    // Find the activity
   $activity = Activity::where('id', $activityId)
                       ->where('user_id', $userId) 
                       ->first();
    if (!$activity) {
       return response()->json(['message' => 'Activity not found or you do not have permission to cancel it'], 404);
   }
    // Delete the activity
   $activity->delete();
    return response()->json(['message' => 'Activity cancelled successfully'], 200);
}

//unjoin activity that the user has joined
public function unjoinActivity(Request $request, $activityId) {

   $userId = $request->user()->id; // Get the authenticated user's ID
    // Check if the user is part of the activity
   $activity = Activity::whereHas('players', function ($query) use ($userId, $activityId) {
                           $query->where('user_id', $userId)
                                 ->where('activity_id', $activityId);
                       })->first();
    if (!$activity) {
       return response()->json(['message' => 'You are not part of this activity'], 404);
   }
    // Detach the user from the activity
   $activity->players()->detach($userId);
    return response()->json(['message' => 'Successfully unjoined the activity'], 200);
}
}
