$('body').ready(function() {

    $("[data-click='printQrsSelected']").removeClass('d-flex');
    $("[data-click='printQrsSelected']").addClass('d-none');

    $('.datatable').DataTable({
        "paging": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        },
        "ordering": false,
    });

    $('body').on('click','#checkAll', function(){
        if($(this).is(':checked')){
            $('input[type="checkbox"]').prop('checked', true);
            if($('input[type="checkbox"]').is(':checked')){
                $("[data-click='printQrsSelected']").removeClass('d-none');
                $("[data-click='printQrsSelected']").addClass('d-flex');
            }
        }else{
            $('input[type="checkbox"]').prop('checked', false);
            $("[data-click='printQrsSelected']").removeClass('d-flex');
            $("[data-click='printQrsSelected']").addClass('d-none');
        }
    });

    $('body').on('click','input[type="checkbox"]', function(){
        if($('input[type="checkbox"]').is(':checked')){
            $("[data-click='printQrsSelected']").removeClass('d-none');
            $("[data-click='printQrsSelected']").addClass('d-flex');
        }else{
            $("[data-click='printQrsSelected']").removeClass('d-flex');
            $("[data-click='printQrsSelected']").addClass('d-none');
        }
    });

    $('body').on('click',"[data-click='printQrsSelected']", function(){
        var ids = [];
        var crmId = $(this).data('crm-id');
        var officeId = $(this).data('office-id');
        $('input[type="checkbox"]:checked').each(function(){
            if($(this).val() != 'on'){
                ids.push($(this).val());
            }
        });
        printQrs(ids,crmId, officeId).done(function(data){
        }).fail(function(error){
            handleErrors(error);
        }); 
    });
});


function printQrs(ids,crmId, officeId){
    var url = ULR + 'crm/mechanism/printQrs' + '?ids=' +  btoa(JSON.stringify(ids)) + '&crmId=' + crmId + '&officeId=' + officeId;
    window.open(url, '_blank');

}


