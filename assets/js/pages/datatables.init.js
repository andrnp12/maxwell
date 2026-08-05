$(document).ready(function () {
  // Turunkan jumlah nomor sebelum ellipsis (default: 7)
  $.fn.DataTable.ext.pager.numbers_length = 4;

  // Custom renderer function untuk child row stacked layout di mobile
  function stackedChildRowRenderer(api, rowIdx, columns) {
    var data = $.map(columns, function (col) {
      if (col.hidden) {
        // Skip kolom "No" (index 0)
        if (col.columnIndex === 0) return null;
        
        return '<li data-dtr-index="' + col.columnIndex + '" data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
          '<span class="dtr-title">' + col.title + '</span>' +
          '<span class="dtr-data">' + col.data + '</span>' +
        '</li>';
      }
    }).join('');

    return data ?
      $('<ul class="dtr-details"/>').append(data) :
      false;
  }

  // Konfigurasi default dengan responsive enabled
  var dtConfig = {
    order: [],
    pagingType: "simple_numbers",
    responsive: {
      details: {
        type: 'inline',
        renderer: stackedChildRowRenderer
      }
    }
  };

  $("#datatable").DataTable(dtConfig);

  $("#datatable-buttons")
    .DataTable({
      lengthChange: !1,
      buttons: ["copy", "excel", "pdf", "colvis"],
      order: [],
      pagingType: "simple_numbers",
      responsive: {
        details: {
          type: 'inline',
          renderer: stackedChildRowRenderer
        }
      }
    })
    .buttons()
    .container()
    .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)");

  // Untuk tabel dengan class .datatable (halaman lain)
  $(".datatable").DataTable({
    order: [],
    responsive: {
      details: {
        type: 'inline',
        renderer: stackedChildRowRenderer
      }
    }
  });

  $(".dataTables_length select").addClass("form-select form-select-sm");
});
