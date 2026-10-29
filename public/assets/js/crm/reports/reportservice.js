var branchOffice = null;
var start_date = null;
var end_date = null;

window.onload = function() {
  
  };

$('body').ready(function(){
    branchOffice = $('select[name="OfficesCustomer"] option').filter(':selected').val();
});

$('body').on('click','#base-reportservice,#tabProcess-tab11', function(e){
    $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office: $('select[name="OfficesCustomer"] option').filter(':selected').val()},
        success: function(data){
            $('#reportserviceDivTable').html(data.view);
        }
    });
});

$('body').on('click', '.page-link', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office:$('select[name="OfficesCustomer"] option').filter(':selected').val(), page:page, perpage: $("[name='tblList_length']").val(), start_date: start_date, end_date: end_date,},
        success: function(data){
            console.log("aaaa",data);
            $('#reportserviceDivTable').html(data.view);
        }
    });
    
});

function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

$('body').on('keyup', "input[type='search']", delay(function(e) {
    return $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office:$('select[name="OfficesCustomer"] option').filter(':selected').val(),start_date: start_date, end_date: end_date, search: this.value, perpage: $("[name='tblList_length']").val(), date: $('input#datetable').val()},
        success: function(data){
            $('#reportserviceDivTable').html(data.view);
        }
    });

},300));

$('body').on('search', "input[type='search']", function(e) {
    return $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office:$('select[name="OfficesCustomer"] option').filter(':selected').val(),start_date: start_date, end_date: end_date, search: this.value, perpage:  $("[name='tblList_length']").val(), date: $('input#datetable').val()},
        success: function(data){
            $('#reportserviceDivTable').html(data.view);
        }
    });
});

$('body').on('change', "input#startdate", function(e) {

    start_date = this.value;
    return $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office:$('select[name="OfficesCustomer"] option').filter(':selected').val(),start_date: start_date, end_date: end_date, perpage: $("[name='tblList_length']").val(), search: $("input[type='search']").val()},
        success: function(data){
            $('#reportserviceDivTable').html(data.view);
        }
    });
});

$('body').on('change', "input#enddate", function(e) {
    end_date = this.value;
    return $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office:$('select[name="OfficesCustomer"] option').filter(':selected').val(),start_date: start_date, end_date: end_date, perpage: $("[name='tblList_length']").val(), search: $("input[type='search']").val()},
        success: function(data){
            $('#reportserviceDivTable').html(data.view);
        }
    });
});

$('body').on('change', "[name='tblList_length']", function(e) {
    return $.ajax({
        url: ULR + "crmreport/report-services",
        method: "GET",
        dataType: "json",
        data: {office:$('select[name="OfficesCustomer"] option').filter(':selected').val(),perpage: $("[name='tblList_length']").val(), start_date: start_date, end_date: end_date, search: $("input[type='search']").val()},
        success: function(data){
            $('#reportserviceDivTable').html(data.view);
        }
    });
});