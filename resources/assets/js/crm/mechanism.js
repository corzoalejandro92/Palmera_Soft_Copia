function saveElement(element, x, y) {
    var $element = $(element);
    let office = $('#office').val();
    var plan = $element.data('plan');
    return $.ajax({
        method: 'get',
        url: ULR + 'crm/mechanism/save/' + $element.data('element'),
        data: {
            file_id: plan,
            x: x,
            y: y,
            office: office,
            size: document.getElementById("customRange").value
        }
    });
}




function updateElement(id, x, y, size, num) {
    return $.ajax({
        method: 'get',
        url: ULR + 'crm/mechanism/update/' + id,
        data: {
            x: x,
            y: y,
            size: size,
            num: num
        }
    });
}