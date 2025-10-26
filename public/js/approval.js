// Kode DataTables Anda yang sudah ada
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#companyHistoryTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [[7, 'desc']], // Urutkan berdasarkan Created At
            "info": true,
            "searching": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    }
});

// --- TAMBAHAN KODE SWEETALERT ---

/**
 * Menampilkan popup konfirmasi untuk MENYETUJUI
 */
function showApproveAlert(companyId, companyName) {
    Swal.fire({
        title: 'Setujui Perusahaan?',
        text: `Anda yakin ingin menyetujui pendaftaran untuk "${companyName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754', // Warna hijau (success)
        cancelButtonColor: '#6c757d',  // Warna abu-abu (secondary)
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, temukan form tersembunyi dan submit
            document.getElementById('form-approve-' + companyId).submit();
        }
    });
}

/**
 * Menampilkan popup konfirmasi untuk MENOLAK
 */
function showRejectAlert(companyId, companyName) {
    Swal.fire({
        title: 'Tolak Perusahaan?',
        text: `Anda yakin ingin menolak pendaftaran untuk "${companyName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545', // Warna merah (danger)
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tolak!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, temukan form tersembunyi dan submit
            document.getElementById('form-reject-' + companyId).submit();
        }
    });
}