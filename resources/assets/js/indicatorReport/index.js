$(document).ready(function() {
    getTableIndicatorMonth();
    getTableIndicatorYear();
});

/******************************************************************************/
//funciones para cargar la tabla indicadores por mes 
function getTableIndicatorMonth(start=null, end=null) {
    var list = $('#indicatorPerMonth');
    $('.loading', list).attr('data-show', 'true');
    loadIndicatorMonth(start, end).done(function(data) {
    $('.loading', list).attr('data-show', 'false');
    list.html(data.view);
    setTimeout(function(){  chartjsMonth(); }, 500);
    }).fail(function(error){
        handleErrors(error);
    });
}

function loadIndicatorMonth(start, end) {
    return $.ajax({
        url:   ULR +"indicators/month",
        method: "GET",
        data: {start: start, end: end},
        dataType: "json"
    });
}
//funciones para cargar la tabla indicadores por año 
function getTableIndicatorYear(start=null, end=null) {
    var list = $('#indicatorPerYear');
        $('.loading', list).attr('data-show', 'true');
    loadIndicatorYear(start, end).done(function(data) {
        $('.loading', list).attr('data-show', 'false');
        list.html(data.view);
        setTimeout(function(){  chartjsYear(); }, 500);
    }).fail(function(error){
        handleErrors(error);
    });
}

function loadIndicatorYear(start, end) {
    return $.ajax({
        url:   ULR +"indicators/year",
        method: "GET",
        data: {start: start, end: end},
        dataType: "json"
    });
}

$('body').on('submit', '#indicatorMonth', function(e) {
    e.preventDefault();
    var start = $("[name='start_date']").val();
    var end = $("[name='end_date']").val();
    getTableIndicatorMonth(start, end);
    getTableIndicatorYear(start);
});



function chartjsMonth(){
    var month = $('#month_new').val();
    var users_name = $('#users_name').val();
    var users_color = $('#users_color').val();
    var users_approved = $('#users_approved').val();
    $('#myChart').remove(); // this is my <canvas> element
    $('#containerCanvas').append('<canvas style="min-width:100%; min-height:100%" id="myChart" ></canvas>');
    var canvas = document.getElementById("myChart");
    if (canvas !== null){
        var ctx = canvas.getContext("2d");
        
        var users_name_array=JSON.parse(users_name);
        var users_color_array=JSON.parse(users_color);
        var users_approved_array=JSON.parse(users_approved);


        const myChartMonth = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: users_name_array,
                datasets: [{
                    label: 'Ventas nuevas del mes de '+month,
                    data: users_approved_array,
                    backgroundColor: users_color_array,
                    borderColor: users_color_array,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                interaction: {
                    mode: 'dataset',
                },
            }
        });
    }

}
function chartjsYear(){
    var year = $('#month_total').val();
    var year_users_name = $('#tyear_users_name').val();
    var year_users_approved = $('#tyear_users_approved').val();
    var year_users_color = $('#tyear_users_color').val(); 

    $('#myCharYear').remove(); // this is my <canvas> element
    $('#containerCanvasYear').append('<canvas style="min-width:100%; min-height:100%" id="myCharYear" ></canvas>');
    var canvasYear = document.getElementById("myCharYear");
    if (canvasYear !== null){
   
        var ctxYear = canvasYear.getContext("2d");
        
        var year_users_name_array=JSON.parse(year_users_name);
        var year_users_approved_array=JSON.parse(year_users_approved);
        var year_users_color_array=JSON.parse(year_users_color);

        const myChartYear = new Chart(ctxYear, {
        type: 'bar',
            data: {
                labels: year_users_name_array,
                datasets: [{
                    label: 'Ventas totales del mes de '+year,
                    data: year_users_approved_array,
                    backgroundColor: year_users_color_array,
                    borderColor: year_users_color_array,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                interaction: {
                    mode: 'dataset',
                },
                
            }
        });
    }
}