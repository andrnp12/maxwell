$(document).ready(function () {
  // Turunkan jumlah nomor sebelum ellipsis (default: 7)
  // Coba nilai 5 atau 6 sampai tampil 1 2 3 4 .. 10
  $.fn.DataTable.ext.pager.numbers_length = 4;

  $("#datatable").DataTable({
    order: [],
    pagingType: "simple_numbers",
  });

  $("#datatable-buttons")
    .DataTable({
      lengthChange: !1,
      buttons: ["copy", "excel", "pdf", "colvis"],
      order: [],
      pagingType: "simple_numbers",
    })
    .buttons()
    .container()
    .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

  $(".dataTables_length select").addClass("form-select form-select-sm");
});
