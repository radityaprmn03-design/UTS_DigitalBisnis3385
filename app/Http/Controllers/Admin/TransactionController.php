<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $query = Transaction::with('event')->latest();
        if (auth()->user()->role !== 'superadmin') {
            $query->whereHas('event', function($q) {
                $q->where('user_id', auth()->id());
            });
        }
        $transactions = $query->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }
}
