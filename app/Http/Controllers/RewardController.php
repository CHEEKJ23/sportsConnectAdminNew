<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPoints;
use App\Models\Gift;
use App\Models\RedemptionRequest;
use Auth;

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

    // Approve/reject redemption (Admin)
    public function updateRedemptionStatus(Request $request, $id)
    {
        $redemption = RedemptionRequest::find($id);
        if (!$redemption) {
            return redirect()->back()->withErrors('Redemption not found');
        }

        $redemption->status = $request->status;
        $redemption->save();

        return redirect()->back()->with('success', 'Redemption status updated');
    }
}

