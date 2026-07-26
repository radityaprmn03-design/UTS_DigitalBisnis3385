<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Check if the user is logged in
        $userId = auth()->check() ? auth()->id() : null;
        $reviewerName = auth()->check() ? auth()->user()->name : $request->input('reviewer_name', 'Guest');

        Review::create([
            'event_id' => $event->id,
            'user_id' => $userId,
            'reviewer_name' => $reviewerName,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan!');
    }
}
