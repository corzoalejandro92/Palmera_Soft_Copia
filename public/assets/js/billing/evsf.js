$(document).ready(function() {
    getList();
});

function getList(start = null, end = null) {
    var list = $('.list-group');
    $('.loading', list).attr('data-show', 'true');
    loadList(start, end).done(function(data) {
        list.html(data.view);
    }).fail(function(error) {
        handleErrors(error);
    });
}

function loadList(start, end) {
    return $.ajax({
        url: ULR + "billing/evsf-list",
        data: {start: start, end: end},
        method: "GET",
        dataType: "json"
    });
}

$('body').on('submit', '#sendScheduleFilter', function(e) {
    e.preventDefault();
    TAB = '#tabSchedule';
    var start = $("[name='start_date']").val();
    var end = $("[name='end_date']").val();
    getList(start, end);

});