<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPoints;
use App\Models\Gift;
use App\Models\RedemptionRequest;
use Auth;
use App\Models\User;

class RewardController extends Controller
{
    // Fetch user points
    public function getUserPoints()
    {
        $points = UserPoints::where('user_id', Auth::id())->first();
        return response()->json($points);
    }

    // Fetch available gifts
    public function getGifts()
    {
        $gifts = Gift::all();
        return response()->json($gifts);
    }

    // Redeem gift
    public function redeemGift(Request $request)
    {
        $gift = Gift::find($request->gift_id);
        $points = UserPoints::where('user_id', Auth::id())->first();

        if (!$gift || !$points || $points->points < $gift->points_needed) {
            return response()->json(['message' => 'Insufficient points'], 400);
        }

        RedemptionRequest::create([
            'user_id' => Auth::id(),
            'gift_id' => $gift->id,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Redemption request submitted']);
    }


    public function viewRedemptions()
{
    $redemptions = RedemptionRequest::with(['user', 'gift'])->get();
    return view('reward&gift.manageRedeem', compact('redemptions'));
}

    // Approve/reject redemption (Admin)
    public function updateRedemptionStatus(Request $request, $id)
{
    $redemption = RedemptionRequest::find($id);
    if (!$redemption) {
        return redirect()->back()->withErrors('Redemption not found');
    }

    // Check if the status is being set to 'approved'
    if ($request->status === 'approved') {
        $userPoints = UserPoints::firstOrCreate(['user_id' => $redemption->user_id]);
        $gift = $redemption->gift; // Assuming you have a relationship set up

        // Check if the user has enough points
        if ($userPoints->points < $gift->points_needed) {
            return redirect()->back()->withErrors('User does not have enough points for this redemption');
        }

        // Deduct points from the user
        $userPoints->points -= $gift->points_needed;
        $userPoints->save();
    }

    // Update the redemption status
    $redemption->status = $request->status;
    $redemption->save();

    return redirect()->back()->with('success', 'Redemption status updated');
}
}

