<div class="modal fade" id="modal-edit-pelanggan" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Data Pelanggan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Form action akan diisi oleh JavaScript --}}
            <form id="form-edit-pelanggan" method="POST"> 
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label for="edit-name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="edit-email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>

                        {{-- No Handphone --}}
                        <div class="col-12">
                            <label for="edit-no_hp" class="form-label">No. Handphone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit-no_hp" name="no_hp" required>
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label for="edit-alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit-alamat" name="alamat" rows="3" required></textarea>
                        </div>

                        {{-- Password (Opsional) --}}
                        <div class="col-md-6">
                            <label for="edit-password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="edit-password" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-md-6">
                            <label for="edit-password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="edit-password_confirmation" name="password_confirmation">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-2"></i>Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>