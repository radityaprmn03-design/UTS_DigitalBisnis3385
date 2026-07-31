<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = \App\Models\Event::with('category')->latest();
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            $query->where('user_id', auth()->id());
        }
        $events = $query->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'poster' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $data['user_id'] = auth()->id();

        \App\Models\Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        abort_if(!in_array(auth()->user()->role, ['superadmin', 'admin']) && $event->user_id !== auth()->id(), 403);
        $categories = \App\Models\Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        abort_if(!in_array(auth()->user()->role, ['superadmin', 'admin']) && $event->user_id !== auth()->id(), 403);
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'poster' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        abort_if(!in_array(auth()->user()->role, ['superadmin', 'admin']) && $event->user_id !== auth()->id(), 403);
        if ($event->poster_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}
