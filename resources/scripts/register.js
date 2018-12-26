import $ from "jquery";

$(function() {
    $('#avatar').on('change', function (e) {
        let fileName = $(e.target).val().replace(/C:\\fakepath\\/i, '');
        $(e.target).next().text(fileName);
    })
})
