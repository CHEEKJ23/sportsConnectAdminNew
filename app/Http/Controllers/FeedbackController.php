<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // User creates feedback
    public function store(Request $request)
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user()->id,
            'feedback' => $request->feedback,
        ]);

        return response()->json(['message' => 'Feedback sent successfully', 'feedback' => $feedback], 201);
    }

    // User views feedback with admin replies
    public function userFeedback(Request $request)
    {
        $feedbacks = Feedback::where('user_id', $request->user()->id)->get();
        return response()->json($feedbacks);
    }

    // Admin views all feedback
    public function index()
    {
        $feedbacks = Feedback::with('user')->get();
        return view('manageFeedback', compact('feedbacks'));
    }

    // Admin replies to feedback
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->update(['reply' => $request->reply]);

        return redirect()->route('feedback.index')->with('success', 'Reply sent successfully.');
    }
}


