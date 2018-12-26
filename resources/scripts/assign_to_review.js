import $ from 'jquery'

$(function () {
    $('#assign-to-review').click(function () {
        const selectedOption = $('#user-id option:selected');
        if (selectedOption.length) {
            const userId = selectedOption.val()

            $.ajax({
                type: 'POST',
                url: assignReviewUrl.replace(),
                data: {
                    article_id: articleId,
                    user_id: userId
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $('#reviewers-table').children('tbody').append(
                            '<tr>' +
                            '<td class="align-middle">' + selectedOption.text() + '</td>' +
                            '<td class="align-middle">Nehodnoceno</td>' +
                            '<td class="align-middle">Nehodnoceno</td>' +
                            '<td class="align-middle">Nehodnoceno</td>' +
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
})
