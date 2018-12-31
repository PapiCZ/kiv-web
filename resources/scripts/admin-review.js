import $ from 'jquery'

$(function () {
    $('#assign-to-review').click(function () {
        const selectedOption = $('#user-id option:selected');
        if (selectedOption.length) {
            const userId = selectedOption.val()

            $.ajax({
                type: 'POST',
                url: assignReviewUrl,
                data: {
                    article_id: articleId,
                    user_id: userId
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $('#reviews-table').children('tbody').append(
                            '<tr>' +
                            '<td class="align-middle">' + selectedOption.text() + '</td>' +
                            '<td class="align-middle">Nehodnoceno</td>' +
                            '<td class="align-middle">Nehodnoceno</td>' +
                            '<td class="align-middle">Nehodnoceno</td>' +
                            '<td class="align-middle"><button type="button" class="btn btn-danger delete-review" data-id="' + response.id + '"><i class="fa fa-times"></i></button></td>' +
                            '</tr>'
                        )

                        selectedOption.prev().attr('selected', 'selected')
                        selectedOption.remove()
                    }
                },
                dataType: 'json'
            });
        }
    });

    $('#reviews-table').on('click', '.delete-review', function () {
        let element = $(this)
        $.ajax({
            type: 'POST',
            url: deleteReviewUrl,
            data: {
                id: $(this).attr('data-id'),
            },
            success: function (response) {
                if (response.status) {
                    element.parentsUntil('tr').parent().remove()
                }
            },
            dataType: 'json'
        });
    });
})
