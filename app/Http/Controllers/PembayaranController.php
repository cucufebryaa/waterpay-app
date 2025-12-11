<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\Pemakaian;
use Barryvdh\Dompdf\Facade\Pdf;


class PembayaranController extends Controller
{
    private function getCompanyId()
    {
        if (Auth::user() && Auth::user()->admin && Auth::user()->admin->id_company) {
            return Auth::user()->admin->id_company;
        }
        return null;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_company = $this->getCompanyId();

        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan perusahaan manapun.');
        }

        // PERBAIKAN DI SINI:
        // Ganti 'tagihan' menjadi 'pemakaian' sesuai nama fungsi relasi di Model Pembayaran
        $dataPembayaran = Pembayaran::where('id_company', $id_company)
                            ->with(['pelanggan', 'pemakaian']) 
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('admin.pembayaran.index', compact('dataPembayaran'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Tidak digunakan, kita pakai modal detail
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tidak digunakan, kita pakai modal konfirmasi
    }

    /**
     * Update the specified resource in storage.
     * Ini adalah fungsi untuk KONFIRMASI atau MENOLAK pembayaran.
     */
    public function update(Request $request, string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        // Validasi input dari modal konfirmasi
        $request->validate([
            'status' => 'required|string|in:Pending,Success,Failed', // Sesuaikan status Anda
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        // Cari pembayaran
        $pembayaran = Pembayaran::where('id', $id)
                        ->where('id_company', $id_company)
                        ->firstOrFail();

        // Update data pembayaran
        $pembayaran->status = $request->status;
        $pembayaran->catatan_admin = $request->catatan_admin;
        $pembayaran->save();

        // LOGIKA BISNIS PENTING:
        // Jika pembayaran Sukses, update status Tagihan terkait menjadi 'Lunas'
        if ($request->status == 'Success' && $pembayaran->id_tagihan) {
            $tagihan = Pemakaian::find($pembayaran->id_tagihan);
            if ($tagihan) {
                $tagihan->status_tagihan = 'Lunas'; // Sesuaikan field Anda
                $tagihan->save();
            }
        }

        return redirect()->route('admin.pembayaran.index')->with('success', 'Status Pembayaran #' . $pembayaran->id . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        $pembayaran = Pembayaran::where('id', $id)
                        ->where('id_company', $id_company)
                        ->firstOrFail();
        
        $pembayaran->delete();

        return redirect()->route('admin.pembayaran.index')->with('success', 'Data Pembayaran #' . $pembayaran->id . ' berhasil dihapus.');
    }

    public function exportPdf()
    {
        $id_company = $this->getCompanyId();

        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        // 1. Ambil data
        $dataPembayaran = Pembayaran::where('id_company', $id_company)
                            ->with(['pelanggan', 'pemakaian']) 
                            ->orderBy('tanggal_bayar', 'desc') 
                            ->get();

        // 2. Siapkan data statistik
        $totalUang = $dataPembayaran->where('status', 'success')->sum('total_bayar');
        $totalSukses = $dataPembayaran->where('status', 'success')->count();
        $totalPending = $dataPembayaran->where('status', 'pending')->count();

        // 3. Muat view menggunakan Helper app('dompdf.wrapper')
        // Ini adalah perubahan krusial.
        $pdf = app('dompdf.wrapper')->loadView('admin.pembayaran.laporan_pdf', [
            'dataPembayaran' => $dataPembayaran,
            'totalUang' => $totalUang,
            'totalSukses' => $totalSukses,
            'totalPending' => $totalPending,
            'tanggalCetak' => now(),
        ]);

        // Atur ukuran kertas
        $pdf->setPaper('A4', 'landscape');
        
        // 4. Unduh file PDF
        return $pdf->stream('Laporan_Pembayaran_' . now()->format('Ymd_His') . '.pdf');
    }
};