$(document).ready(function() {
    initSelectSheetOption();
});

function initSelectSheetOption() {

    selector = '[name*="sheet_to_import[]"]';
    $(selector).select2({
        multiple: true,
        placeholder: "Seleccione una o mas hojas"
    });
}