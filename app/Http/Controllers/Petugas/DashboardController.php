<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data Metrik (Dummy) ---
        $totalTugasCatat = 120; // Total pelanggan yg jadi tugasnya
        $pencatatanSelesai = 85;  // Selesai bulan ini
        $keluhanBaru = 3;         // Keluhan status 'diproses'
        $keluhanSelesai = 15;     // Keluhan status 'selesai'

        // --- Data Chart Awal (7 Hari Terakhir) ---
        $defaultEndDate = Carbon::now();
        $defaultStartDate = Carbon::now()->copy()->subDays(6);

        // Panggil helper untuk data chart dummy
        $chartData = $this->prepareReadingsChartData($defaultStartDate, $defaultEndDate);

        return view('petugas.dashboard', compact(
            'totalTugasCatat',
            'pencatatanSelesai',
            'keluhanBaru',
            'keluhanSelesai',
            'chartData',
            'defaultStartDate',
            'defaultEndDate'
        ));
    }

    /**
     * Mengambil data untuk filter chart (AJAX - DUMMY DATA).
     */
    public function getChartData(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Panggil helper
        $data = $this->prepareReadingsChartData($startDate, $endDate);

        return response()->json($data);
    }

    /**
     * Helper untuk memproses data chart pencatatan (Harian - DUMMY).
     */
    private function prepareReadingsChartData(Carbon $startDate, Carbon $endDate)
    {
        $labels = [];
        $data = [];

        // Buat rentang per hari
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            // Label: 19 Okt
            $labels[] = $date->locale('id')->isoFormat('DD MMM');
            // Data acak antara 5 s/d 25 pencatatan per hari
            $data[] = rand(5, 25);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
