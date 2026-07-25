<!-- Toast sukses dan gagal -->

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        Apakah Anda yakin ingin menghapus? Data yang dihapus tidak dapat dikembalikan.
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <!-- Tombol aksi hapus di dalam modal -->
        <button type="button" class="btn btn-danger" id="btnEksekusiHapus">Ya, Hapus</button>
      </div>

    </div>
  </div>
</div>
<div
  class="position-fixed p-3 top-0 end-0"
  id="modalNotifikasi"
  tabindex="-1"
  aria-labelledby="modalNotifikasiLabel"
  aria-hidden="true"
  style="z-index: 2000">
  <div
    aria-atomic="true"
    aria-live="assertive"
    class="toast fade hide text-white"
    role="alert">
    <div class="toast-header text-white border-1">
      <img alt="" class="me-2" height="18" src="../assets/images/logo-sm.svg" />
      <strong class="me-auto" id="judulNotifikasi"></strong>
      <button
        aria-label="Close"
        class="btn-close btn-close-white"
        data-bs-dismiss="toast"
        type="button"></button>
    </div>
    <div class="toast-body text-white" id="toastBody">
      <small id="pesanNotifikasi"></small>
    </div>
  </div>
</div>