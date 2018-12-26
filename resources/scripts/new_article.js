import $ from 'jquery'

$(function () {
    $('#add-document-input').click(function () {
        $('#documents').append($('#document-file-template').clone().html())
    });

    $('#add-document-input').click()

    $('#documents, #image').on('change', function (e) {
        let fileName = $(e.target).val().replace(/C:\\fakepath\\/i, '');
        $(e.target).next().text(fileName);
    })

    $('#documents').on('click', '.delete-document', function () {
        let id;
        if (id = $(this).data('id')) {
            $('#article-form').append('<input type="hidden" name="remove_documents[]" value="' + id + '">')
        }

        $(this).parent().parent().remove()
    })

    $('#perex').bind('keyup change', function () {
        $('#perex').prev('label').html('<label for="perex">Perex <small>(' + $(this).val().length + '/' + $(this).attr('maxlength') + ')</small></label>')
    })

    $('#perex').keyup()
})
