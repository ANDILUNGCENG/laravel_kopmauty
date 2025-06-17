
$(document).ready(function() {
    $('.datatables-x').DataTable({
        dom: '<"card-header"<"head-label text-center"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        buttons: [
            {
                text: '<i class="bx bx-plus me-1"></i> <span class="d-none d-lg-inline-block">Add '+titleobject+'</span>',
                className: 'create-new btn btn-primary',
                attr: {
                    'data-bs-toggle': 'modal',
                    'data-bs-target': '#addModal'
                }
            }
        ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function(row) {
              var data = row.data();
              return 'Details of '+titleobject+'';
            }
          }),
          type: 'column',
          renderer: function(api, rowIdx, columns) {
            var data = $.map(columns, function(col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },
        columnDefs: [
            {
                className: 'control',
                orderable: false,
                responsivePriority: 2,
                targets: 0
            }
        ],
        order: [[1, 'asc']]  // Adjust the default ordering
    });
    $('div.head-label').html('<h5 class="card-title mb-0">List '+titleobject+'</h5>');
});