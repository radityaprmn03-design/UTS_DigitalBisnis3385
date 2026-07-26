<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Memakai relasi dan pengaturan limit paginasi (10 entri per halaman)
        $events = \App\Models\Event::with('category')->latest()->paginate(10);
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
        // Menerapkan validasi data request dari pengguna
        $data = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model
        \App\Models\Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Event $event)
{
   // Mengambil daftar kategori untuk keperluan menu footer
    $categories = \App\Models\Category::all();
    
    // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
    $event->load('reviews.user');
    return view('event-detail', compact('categories', 'event'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $categories = \App\Models\Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);
    
        $event->update($data);
    
        return redirect()->route('admin.events.index')->with('success', 'Rincian data event berhasil diperbarui.');    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus secara permanen.');
    }

    /**
     * Tampilkan E-Ticket milik pengguna yang sedang login
     */
    public function ticket()
    {
        $categories = \App\Models\Category::all();

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat tiket Anda.');
        }

        $tickets = \App\Models\Transaction::with('event')
            ->where('customer_email', auth()->user()->email)
            ->whereIn('status', ['success', 'settlement', 'used'])
            ->latest()
            ->get();

        return view('ticket', compact('tickets', 'categories'));
    }
}
