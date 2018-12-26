import 'bootstrap/js/dist/popover'
import 'bootstrap/js/dist/dropdown'
import modal from 'bootstrap/js/dist/modal'
import $ from 'jquery'

$(function () {
    let loginForm = $("#login-popover").html();
    $("#login-popover").remove()
    $('#login-popover-btn').popover({
        container: 'body',
        html: true,
        content: loginForm
    }).on('shown.bs.popover', function () {
        $(".login-form").submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: loginUrl,
                data: {
                    'username': $('#login-username').val(),
                    'password': $('#login-password').val()
                },
                success: function (response) {
                    if (response.status == 'error') {
                        $('#login-username').addClass('is-invalid')
                        $('#login-password').addClass('is-invalid')
                        $('#login-error-alert').removeClass('d-none')
                    } else {
                        location.reload()
                    }
                },
                dataType: 'json'
            });
        })
    })

    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor.create(document.querySelector('.ckeditor')).then(editor => {
            console.log(editor);
        }).catch(error => {
            console.error(error);
        });
    }
})
