<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use App\Models\Pemakaian;
use Barryvdh\Dompdf\Facade\Pdf;


use Carbon\Carbon;

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
     * Private method to get filtered data query
     */
    private function getFilteredData(Request $request, $id_company)
    {
        $query = Pembayaran::where('id_company', $id_company)
                            ->with(['pelanggan', 'pemakaian.petugas']); // Eager load petugas via pemakaian

        // Filter by Month
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_bayar', $request->bulan);
            
            // If month is selected but year is NOT, default to current year
            // to prevent pulling data from all years for that month (?)
            // Or let user control it explicitly. 
            // Usually if Month selected, Year is expected.
            if (!$request->filled('tahun')) {
                $query->whereYear('tanggal_bayar', now()->year);
            }
        }

        // Filter by Year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_bayar', $request->tahun);
        }

        // Filter by Search (Pelanggan Name or Petugas Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pelanggan', function($qPelanggan) use ($search) {
                    $qPelanggan->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('pemakaian', function($qPemakaian) use ($search) {
                    $qPemakaian->whereHas('petugas', function($qPetugas) use ($search) {
                        $qPetugas->where('nama', 'like', "%{$search}%");
                    });
                });
            });
        }

        return $query->orderBy('tanggal_bayar', 'desc');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id_company = $this->getCompanyId();

        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan perusahaan manapun.');
        }

        // Get Filtered Data
        $dataPembayaran = $this->getFilteredData($request, $id_company)->get();

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

    public function exportPdf(Request $request)
    {
        $id_company = $this->getCompanyId();

        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        // 1. Ambil data dengan fiter yang SAMA
        $dataPembayaran = $this->getFilteredData($request, $id_company)->get();

        // 2. Siapkan data statistik (Statistik juga harus mengikuti filter jika diinginkan, 
        // tapi biasanya statistik di header PDF mencerminkan data yang DITAMPILKAN)
        // Jadi kita hitung dari $dataPembayaran hasil filter.
        $totalUang = $dataPembayaran->where('status', 'success')->sum('total_bayar');
        $totalSukses = $dataPembayaran->where('status', 'success')->count();
        $totalPending = $dataPembayaran->where('status', 'pending')->count();

        // 3. Siapkan Filename & Label
        $fileName = 'Laporan_Pembayaran';
        $periodeLabel = 'Semua Riwayat Transaksi';

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $monthName = Carbon::create()->month((int)$request->bulan)->translatedFormat('F');
            $fileName .= '_' . $monthName . '_' . $request->tahun;
            $periodeLabel = $monthName . ' ' . $request->tahun;
        } elseif ($request->filled('tahun')) {
            $fileName .= '_Tahun_' . $request->tahun;
            $periodeLabel = 'Tahun ' . $request->tahun;
        } else {
            $fileName .= '_' . now()->format('Ymd_His');
        }

        $fileName .= '.pdf';

        // 4. Load View
        $pdf = app('dompdf.wrapper')->loadView('admin.pembayaran.laporan_pdf', [
            'dataPembayaran' => $dataPembayaran,
            'totalUang' => $totalUang,
            'totalSukses' => $totalSukses,
            'totalPending' => $totalPending,
            'tanggalCetak' => now(),
            'periodeLabel' => $periodeLabel,
        ]);

        // 5. Set Paper & Stream
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($fileName);
    }
}