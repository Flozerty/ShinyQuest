$(document).ready(function () {
    $('.increment').click(function () {

        // <button class="decrement" data-id="{{ capture.id }}">-</button>
        let captureId = $(this).data('id');
        $.ajax({
            url: '/shasse/' + captureId + '/increment',
            type: 'POST',
            success: function (data) {

                //   <p class="compteur" id="compteur-{{ capture.id }}">{{capture.nbRencontres}}</p>
                $('#compteur-' + captureId).text(data.nbRencontres);
            }
        });
    });

    $('.decrement').click(function () {
        let captureId = $(this).data('id');
        $.ajax({
            url: '/shasse/' + captureId + '/decrement',
            type: 'POST',
            success: function (data) {
                $('#compteur-' + captureId).text(data.nbRencontres);
            }
        });
    });
});