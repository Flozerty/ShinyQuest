$(document).ready(function () { // ~= document.addEventListener('DOMContentLoaded', () {

    $('.increment').click(function () {

        // <button class="decrement" data-id="{{ capture.id }}">-1</button>
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

    $('.increment10').click(function () {
        let captureId = $(this).data('id');
        $.ajax({
            url: '/shasse/' + captureId + '/increment10',
            type: 'POST',
            success: function (data) {
                $('#compteur-' + captureId).text(data.nbRencontres);
            }
        });
    });

    $('.decrement10').click(function () {
        let captureId = $(this).data('id');
        $.ajax({
            url: '/shasse/' + captureId + '/decrement10',
            type: 'POST',
            success: function (data) {
                $('#compteur-' + captureId).text(data.nbRencontres);
            }
        });
    });
});