<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Data Metrik (Dummy) ---
        $tagihanBelumBayar = 2;
        $tagihanLunas = 10;
        $totalKeluhan = 1;
        
        // Data dummy untuk tagihan terakhir (bisa null)
        $tagihanTerakhir = (object) [
            'periode' => Carbon::now()->subMonth()->format('F Y'),
            'jumlah' => 125000 
        ];

        // --- Data Chart Awal (6 Bulan Terakhir) ---
        $defaultEndDate = Carbon::now();
        // 5 bulan lalu + bulan ini = 6
        $defaultStartDate = Carbon::now()->subMonths(5)->startOfMonth(); 

        // Panggil helper untuk data chart dummy
        $chartData = $this->prepareUsageChartData($defaultStartDate, $defaultEndDate);

        return view('pelanggan.dashboard', compact(
            'tagihanBelumBayar',
            'tagihanLunas',
            'totalKeluhan',
            'tagihanTerakhir',
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
        $data = $this->prepareUsageChartData($startDate, $endDate);

        return response()->json($data);
    }

    /**
     * Helper untuk memproses data chart pemakaian air (Bulanan - DUMMY).
     */
    private function prepareUsageChartData(Carbon $startDate, Carbon $endDate)
    {
        $labels = [];
        $data = [];

        // Buat rentang per bulan
        $period = CarbonPeriod::create($startDate->startOfMonth(), '1 month', $endDate->endOfMonth());

        foreach ($period as $date) {
            // Label: Okt 2025
            $labels[] = $date->locale('id')->isoFormat('MMM YYYY'); 
            // Data acak antara 10 s/d 30 m³
            $data[] = rand(10, 30); 
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
