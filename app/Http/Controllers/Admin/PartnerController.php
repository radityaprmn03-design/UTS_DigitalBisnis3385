<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of partners.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $partners = Partner::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        $editPartner = null;
        if ($request->has('edit')) {
            $editPartner = Partner::find($request->input('edit'));
        }

        return view('admin.partners.index', compact('partners', 'editPartner', 'search'));
    }

    /**
     * Store a newly created partner in database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url|max:255',
        ]);

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner baru berhasil ditambahkan.');
    }

    /**
     * Update the specified partner in database.
     */
    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url|max:255',
        ]);

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Data partner berhasil diperbarui.');
    }

    /**
     * Remove the specified partner from database.
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus secara permanen.');
    }
}
