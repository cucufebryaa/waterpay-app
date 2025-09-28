<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index()
    {
        // --- Nanti, ganti ini dengan query database asli ---
        $totalCompanies = 56; // Contoh: Company::count();
        $pendingCompanies = 8;  // Contoh: Company::where('status', 'pending')->count();
        $approvedCompanies = 45; // Contoh: Company::where('status', 'approved')->count();
        $rejectedCompanies = 3;  // Contoh: Company::where('status', 'rejected')->count();

        $defaultEndDate = Carbon::now();
        $defaultStartDate = Carbon::now()->subDays(6);

        // Data dummy untuk chart 7 hari terakhir
        $chartLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $chartData = [2, 5, 3, 6, 4, 7, 1];
        // --- Akhir data dummy ---

        return view('superadmin.dashboard', compact(
            'totalCompanies',
            'pendingCompanies',
            'approvedCompanies',
            'rejectedCompanies',
            'chartData',
            'defaultStartDate',
            'defaultEndDate',
            'chartLabels',
            
        ));
    }

    public function getChartData(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $data = $this->prepareChartData($startDate, $endDate);

        return response()->json($data);
    }

    /**
     * Helper function untuk menyiapkan data chart.
     * PENTING: Nanti ganti logika di sini dengan query database asli Anda.
     */
    private function prepareChartData(Carbon $startDate, Carbon $endDate)
    {
        $labels = [];
        $data = [];
        
        // Loop untuk setiap hari dalam rentang tanggal
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $labels[] = $date->format('d M'); // Format tanggal (e.g., 21 Sep)
            
            // LOGIKA DUMMY: Hasilkan angka acak
            // GANTI INI DENGAN QUERY ASLI:
            // $count = Company::whereDate('created_at', $date)->count();
            $data[] = rand(0, 10);
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
