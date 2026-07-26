<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperadmin = $user->role === 'superadmin';

        $transactionsQuery = Transaction::query();
        if (!$isSuperadmin) {
            $transactionsQuery->whereHas('event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $eventsQuery = Event::query();
        if (!$isSuperadmin) {
            $eventsQuery->where('user_id', $user->id);
        }

        // 1. Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas
        $totalRevenue = (clone $transactionsQuery)->whereIn('status', ['settlement', 'success'])->sum('total_price');
        
        // 2. Menghitung Berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = (clone $transactionsQuery)->whereIn('status', ['settlement', 'success'])->count();
        
        // 3. Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = (clone $eventsQuery)->where('date', '>=', now())->count();
        
        // 4. Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Expired)
        $pendingOrders = (clone $transactionsQuery)->where('status', 'pending')->count();
        
        // 5. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = (clone $transactionsQuery)->with('event')->latest()->take(5)->get();

        // 6. Data Grafik Pertumbuhan Event
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $monthExpr = ($driver === 'sqlite') ? "cast(strftime('%m', created_at) as integer)" : "MONTH(created_at)";

        $eventGrowth = (clone $eventsQuery)->selectRaw("{$monthExpr} as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $chartLabels = [];
        $chartData = [];
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        foreach ($eventGrowth as $data) {
            $monthIndex = ((int) $data->month) - 1;
            if (isset($months[$monthIndex])) {
                $chartLabels[] = $months[$monthIndex];
                $chartData[] = $data->count;
            }
        }

        return view('admin.dashboard', compact('totalRevenue', 'ticketsSold', 'activeEvents', 'pendingOrders', 'recentTransactions', 'chartLabels', 'chartData'));
    }
}