<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Company;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Query Database Asli untuk Statistik ---
        $totalCompanies = Company::count();
        $pendingCompanies = Company::where('status', 'pending')->count();
        $approvedCompanies = Company::where('status', 'approved')->count();
        $rejectedCompanies = Company::where('status', 'rejected')->count();

        // --- Query Database Asli untuk Chart 7 Hari Terakhir ---

        // Tentukan rentang tanggal (7 hari terakhir)
        $defaultEndDate = Carbon::now();
        $defaultStartDate = Carbon::now()->subDays(6);

        // Siapkan array untuk menampung data chart
        $chartLabels = [];
        $dailyCounts = [];

        // Buat rentang tanggal dari awal sampai akhir
        $period = CarbonPeriod::create($defaultStartDate, $defaultEndDate);

        // Inisialisasi data chart dengan 0 untuk setiap hari
        foreach ($period as $date) {
            // 'id' untuk format hari Bahasa Indonesia (Sen, Sel, Rab, ...)
            $chartLabels[] = $date->locale('id')->isoFormat('ddd'); 
            // Gunakan format Y-m-d sebagai kunci unik
            $dailyCounts[$date->format('Y-m-d')] = 0; 
        }

        // Ambil data registrasi perusahaan dari database
        $companyRegistrations = Company::whereBetween('created_at', [$defaultStartDate->startOfDay(), $defaultEndDate->endOfDay()])
                                     ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                     ->groupBy('date')
                                     ->orderBy('date', 'asc')
                                     ->get()
                                     ->pluck('count', 'date'); // Hasil: ['2025-10-13' => 2, '2025-10-15' => 5]

        // Masukkan data dari database ke array $dailyCounts
        foreach ($companyRegistrations as $date => $count) {
            if (isset($dailyCounts[$date])) {
                $dailyCounts[$date] = $count;
            }
        }

        // $chartData sekarang berisi data hitungan berurutan sesuai $chartLabels
        $chartData = array_values($dailyCounts);
        // --- Akhir Query Database Asli ---

        return view('superadmin.dashboard', compact(
            'totalCompanies',
            'pendingCompanies',
            'approvedCompanies',
            'rejectedCompanies',
            'chartData',
            'defaultStartDate',
            'defaultEndDate',
            'chartLabels'
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

        // Panggil helper function yang sudah diperbarui
        $data = $this->prepareChartData($startDate, $endDate);

        return response()->json($data);
    }

    /**
     * Helper function untuk menyiapkan data chart dengan query database asli.
     */
    private function prepareChartData(Carbon $startDate, Carbon $endDate)
    {
        $labels = [];
        $dailyCounts = []; // Array sementara untuk menampung data (Tgl -> Jumlah)

        // 1. Inisialisasi: Buat semua label dan set data ke 0 untuk setiap hari
        $period = CarbonPeriod::create($startDate, $endDate);
        
        foreach ($period as $date) {
            // Label untuk chart (e.g., 19 Okt)
            $labels[] = $date->locale('id')->isoFormat('DD MMM'); 
            // Kunci unik untuk data (e.g., 2025-10-19)
            $dailyCounts[$date->format('Y-m-d')] = 0;
        }

        // 2. Query: Ambil data registrasi dalam satu query
        $companyRegistrations = Company::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
                                     ->select(
                                         DB::raw('DATE(created_at) as date'),
                                         DB::raw('COUNT(*) as count')
                                     )
                                     ->groupBy('date')
                                     ->orderBy('date', 'asc')
                                     ->get()
                                     // Ubah hasil query menjadi format [tanggal => jumlah]
                                     ->pluck('count', 'date'); 

        // 3. Merge: Gabungkan data dari query ke array $dailyCounts
        foreach ($companyRegistrations as $date => $count) {
            // Jika tanggal dari database ada di array kita, update nilainya
            if (isset($dailyCounts[$date])) {
                $dailyCounts[$date] = $count;
            }
        }

        // 4. Finalisasi: Kembalikan label dan data (hanya nilainya)
        return [
            'labels' => $labels,
            'data' => array_values($dailyCounts), // [0, 5, 2, 0, ...]
        ];
    }
}
