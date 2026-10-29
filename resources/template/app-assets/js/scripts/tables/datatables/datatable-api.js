

// DataTable
var spanish= {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
};

$(document).ready(function () {
    var tableSearching = $('.text-inputs-searching').DataTable(
        {
            "language": spanish,
            dom:"<'row mb-2'<'col-sm-12'<'float-right' B>>><'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copiar',
                    exportOptions: {
                        columns: ':not(.nofilter)'
                    }
                },{
                    extend: 'excelHtml5',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':not(.nofilter)'
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Seleccionar columnas'
                }
            ],
            columnDefs: [{
                targets: [0],
                orderable: false
            }],
            order: [[1, "desc"]],
            colReorder: false,
            responsive:true,
            initComplete: function () {
                this.api().columns().every(function () {
                    if (!$(this.header()).hasClass("nofilter")) {
                        var column = this;
                        if ($(this.header()).hasClass("htmlfilter")) {
                            var select = $('<select multiple title="Filtrar"></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        var val = $(this).val();
                                        val=$.map(val,function(x){ return $.fn.dataTable.util.escapeRegex(x); });
                                        column.search(val.length>0 ? val.join("|") : '', true, false).draw();
                                    });
    
                            column.data().unique().sort().each(function (d, j) {
                                if (d != "") {
                                    if (d != "No disponible") {
                                        d = $(d)[0].innerHTML;
                                    }
                                    select.append('<option value="' + d + '">' + d + '</option>')
                                }
                            });
                        } else {
                            var select = $('<select multiple title="Filtrar" class="selects-filters"></select>')
                                    .appendTo($(column.footer()).empty())
                                    .on('change', function () {
                                        var val = $(this).val();
                                        val=$.map(val,function(x){ return $.fn.dataTable.util.escapeRegex(x); });
                                        column.search(val.length>0 ? '^' + val.join("|") + '$' : '', true, false).draw();
                                    });
    
                            column.data().unique().sort().each(function (d, j) {
                                if (d != "") {
                                    select.append('<option value="' + d + '">' + d + '</option>')
                                }
                            });
                        }
                        select.selectpicker({
                            width:($(this.header()).width()<300)?$(this.header()).width():150,
                            liveSearch:true,
                            container:"body"
                        });
                    }
                });
    
                
            }
        }
    );

    $('body').on('click', 'td[tabindex="0"]', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
           .columns.adjust()
           .responsive.recalc();
    });    
});