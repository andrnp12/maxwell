$(document).ready(function () {
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

  ($(".datatable").DataTable({ 
    order: [],
    responsive: {
      details: {
        type: 'inline',
        renderer: stackedChildRowRenderer
      }
    }
  }), $(".dataTables_length select").addClass("form-select form-select-sm"));
});
